<?php require("my_db_connect.php"); ?>

<div id="searchSpecs">
    <!-- Basic search bar -->
    <form method="get" action="">
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="form-group">
                    <label for="keyword" class="sr-only">Search keyword:</label>
                    <div class="input-group w-100">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent pr-0 text-muted">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control border-left-0" name="keyword" id="keyword"
                            placeholder="Search for anything">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced search dropdown bar -->
        <button class="btn btn-secondary mb-2" type="button" data-toggle="collapse" data-target="#advancedSearch"
            aria-expanded="false" aria-controls="advancedSearch">
            Advanced search
        </button>
        <div class="collapse" id="advancedSearch">
            <div class="row mt-2">
                <!-- Category Dropdown -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cat">Category:</label>
                        <select class="form-control" name="cat" id="cat">
                            <option selected value="">All categories</option>
                            <?php
                            // Fetch categories from the database
                            $query = "SELECT DISTINCT category FROM auctions;";
                            $result_q = mysqli_query($con, $query);

                            if ($result_q) {
                                while ($row_q = mysqli_fetch_assoc($result_q)) {
                                    // Echo each category as an option
                                    echo '<option value="' . htmlspecialchars($row_q['category']) . '">' . htmlspecialchars($row_q['category']) . '</option>';
                                }
                            } else {
                                // If query fails, show an error message in the dropdown
                                echo '<option value="">Error loading categories</option>';
                            }
                            ?>
                        </select>
                        <small class="form-text text-muted">Select a category to filter results.</small>
                    </div>
                </div>

                <!-- Sort by Dropdown -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="order_by">Sort by:</label>
                        <select class="form-control" name="order_by" id="order_by">
                            <option selected value="startPrice">Starting price (low to high)</option>
                            <option value="startPrice DESC">Starting price (high to low)</option>
                            <option value="endDate DESC">Ending date</option>
                        </select>
                        <small class="form-text text-muted">Choose how to sort the results.</small>
                    </div>
                </div>

                <!-- Search type Dropdown -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="search_type">Search type:</label>
                        <select class="form-control" name="search_type" id="search_type">
                            <option selected value="intersection">Intersection search</option>
                            <option value="union">Union search</option>
                        </select>
                        <small class="form-text text-muted">Choose the type of search method.</small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div> <!-- end search specs bar -->

<script>
    // Function to populate form fields from URL parameters
    function syncFormWithUrl() {
        // Get URL parameters as a URLSearchParams object
        const params = new URLSearchParams(window.location.search);

        // Get references to the form fields
        const keywordInput = document.getElementById('keyword');
        const catSelect = document.getElementById('cat');
        const orderBySelect = document.getElementById('order_by');
        const searchTypeSelect = document.getElementById('search_type');

        // Set the form fields with the parameter values, if they exist
        if (params.has('keyword')) {
            keywordInput.value = params.get('keyword');
        }

        if (params.has('cat')) {
            catSelect.value = params.get('cat');
        }

        if (params.has('order_by')) {
            orderBySelect.value = params.get('order_by');
        }

        if (params.has('search_type')) {
            searchTypeSelect.value = params.get('search_type');
        }
    }

    // Synchronise form when the page loads
    document.addEventListener('DOMContentLoaded', syncFormWithUrl);
</script>