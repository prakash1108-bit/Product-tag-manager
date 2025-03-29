<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rule;
use App\Models\RuleCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuleController extends Controller
{
    public function index()
    {
        $rules = Rule::get();
        return view('rules.index', ['rules' => $rules]);
    }
    public function create()
    {
        return view('rules.create');
    }

    /**
     * Store a newly created rule in the database.
     *
     * This function validates the incoming request, creates a new rule along with its conditions,
     * and commits the transaction. If an error occurs, the transaction is rolled back, and an error
     * message is returned.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request containing the rule data
     * @return \Illuminate\Http\RedirectResponse  Redirects to the rules index page with success or error message
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'tags' => 'required|string',
                'conditions' => 'required|array',
            ]);
            $data['name'] = $request->name;
            $data['tags'] = $request->tags;
            DB::beginTransaction();
            $rule = Rule::create($data);
            if (isset($request->conditions)) {
                $condtions = $request->conditions;
                foreach ($condtions as $key => $value) {
                    RuleCondition::create([
                        'rule_id' => $rule->id,
                        'product_selector' => $value['product_selector'],
                        'operator' => $value['operator'],
                        'value' => $value['value'],
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('rules.index')->with('success', 'Rule created successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('rules.index')->with('error', $th->getMessage());
        }
    }
    /**
     * Apply the selected rule to all products.
     *
     * This function checks all the products and applies the tags associated with the
     * rule if the product satisfies all the conditions.
     *
     * @param  int  $ruleId  The ID of the rule to apply
     * @return \Illuminate\Http\RedirectResponse  The response redirecting back with success or error message
     */
    public function applyRule($ruleId)
    {
        try {
            $rule = Rule::with('conditions')->find($ruleId);
            if (!$rule) {
                return redirect()->back()->with('error', 'Rule not found.');
            }

            $products = Product::all();
            foreach ($products as $product) {
                $conditionsMatched = true;
                foreach ($rule->conditions as $condition) {
                    $productValue = $product->{$condition->product_selector};
                    if (!$this->checkCondition($productValue, $condition)) {
                        $conditionsMatched = false;
                        break;
                    }
                }

                if ($conditionsMatched) {
                    $existingTags = explode(',', $product->tags);
                    $existingTags = array_map('trim', $existingTags);
                    $newTags = explode(',', $rule->tags);
                    $newTags = array_map('trim', $newTags);
                    $mergedTags = array_unique(array_merge($existingTags, $newTags));
                    $mergedTags = array_filter($mergedTags, function ($tag) {
                        return !empty($tag);
                    });
                    $mergedTags = array_values($mergedTags);
                    $product->tags = implode(',', $mergedTags);
                    $product->save();
                }
            }

            return redirect()->back()->with('success', 'Rule applied successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    /**
     * Check if the product attribute matches the rule condition.
     *
     * This function compares the product's attribute value with the specified condition
     * and operator to check if the condition is met.
     *
     * @param  mixed  $productValue  The value of the product attribute to compare
     * @param  \App\Models\Condition  $condition  The condition to check against
     * @return bool  True if the condition is met, otherwise false
     */
    protected function checkCondition($productValue, $condition)
    {
        switch ($condition->operator) {
            case '==':
                return $productValue == $condition->value;
            case '>':
                return $productValue > $condition->value;
            case '<':
                return $productValue < $condition->value;
            default:
                return false;
        }
    }
}
