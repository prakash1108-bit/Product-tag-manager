<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Rule</title>
    <!-- Bootstrap CSS (Using Laravel's asset helper) -->
    <link href="{{ asset('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <div class="d-flex align-items-center gap-3">
            <h2 class="mr-3">Create Rule</h2>
            <a class="btn btn-primary" href="{{route('rules.index')}}">Back to index page</a>
        </div>
        <form method="POST" action="{{ route('rules.store') }}">
            @csrf
            <!-- Rule Name -->
            <div class="form-group">
                <label for="ruleName">Rule Name</label>
                <input type="text" class="form-control" id="ruleName" name="name"
                    placeholder="Enter rule name">
            </div>

            <!-- Rule Conditions -->
            <div id="conditionsWrapper">
                <div class="form-group condition">
                    <label for="productSelector1">Choose Product Selector</label>
                    <select class="form-control" id="productSelector1" name="conditions[0][product_selector]">
                        <option value="type">Type</option>
                        <option value="sku">SKU</option>
                        <option value="vendor">Vendor</option>
                        <option value="price">Price</option>
                        <option value="qty">Qty</option>
                    </select>
                </div>

                <div class="form-group condition">
                    <label for="operator1">Choose Operator</label>
                    <select class="form-control" id="operator1" name="conditions[0][operator]">
                        <option value="==">==</option>
                        <option value=">">></option>
                        <option value="<"><</option>
                    </select>
                </div>

                <div class="form-group condition">
                    <label for="value1">Enter Value</label>
                    <input type="text" class="form-control" id="value1" name="conditions[0][value]"
                        placeholder="Enter value">
                </div>
            </div>

            <button type="button" class="btn btn-secondary" id="addConditionBtn">Add Condition</button>

            <!-- Apply Tags -->
            <div class="form-group mt-4">
                <label for="tags">Apply Tags</label>
                <input type="text" class="form-control" id="tags" name="tags"
                    placeholder="Enter tags (comma separated)">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#addConditionBtn').click(function() {
                var conditionIndex = $('.condition').length;

                var newCondition = $('<div>', {
                    class: 'form-group condition'
                });

                newCondition.append(`
            <label for="productSelector${conditionIndex}">Choose Product Selector</label>
            <select class="form-control" id="productSelector${conditionIndex}" name="conditions[${conditionIndex}][product_selector]">
                <option value="type">Type</option>
                <option value="sku">SKU</option>
                <option value="vendor">Vendor</option>
                <option value="price">Price</option>
                <option value="qty">Qty</option>
            </select>
        `);

                var operatorCondition = $('<div>', {
                    class: 'form-group condition'
                });
                operatorCondition.append(`
            <label for="operator${conditionIndex}">Choose Operator</label>
            <select class="form-control" id="operator${conditionIndex}" name="conditions[${conditionIndex}][operator]">
                <option value="==">==</option>
                <option value=">">></option>
                <option value="<"><</option>
            </select>
        `);

                var valueCondition = $('<div>', {
                    class: 'form-group condition'
                });
                valueCondition.append(`
            <label for="value${conditionIndex}">Enter Value</label>
            <input type="text" class="form-control" id="value${conditionIndex}" name="conditions[${conditionIndex}][value]" placeholder="Enter value">
        `);

                newCondition.append(operatorCondition).append(valueCondition);
                $('#conditionsWrapper').append(newCondition);
            });
        });
    </script>

</body>

</html>
