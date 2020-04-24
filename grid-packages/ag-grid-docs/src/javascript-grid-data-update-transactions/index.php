<?php
$pageTitle = "Updating Data: Core Feature of our Datagrid";
$pageDescription = "Core feature of ag-Grid supporting Angular, React, Javascript and more. One such feature is Updating Data. Data can be updated in real time. The grid can highlight the change by flashing the cells or by animation inside the cell as the cell refreshes. Version 20 is available for download now, take it for a free two month trial.";
$pageKeywords = "ag-Grid Insert Remove";
$pageGroup = "feature";
include '../documentation-main/documentation_header.php';
?>

    <h1>Client-side Data - Transaction Updates</h1>

    <p class="lead">
        Transaction Updates allow adding, removing or updating large numbers of rows inside the grid
        in an efficient manner.
    </p>

    <p>
        Transaction Updates are excellent for applying large data changes with the following advantages:
    </p>
    <ul>
        <li>Efficient operation.</li>
        <li>Updates sort, filter, group, aggregation and pivot after changes applied.</li>
    </ul>

    <p>
        The only disadvantage of transactions is if you are applying a stream of updates to the grid
        (eg multiple updates a second). For streaming updates, you can get the grid to apply transactions
        in batches which is detailed in the section
        <a href="../javascript-grid-data-update-high-frequency/">High Frequency</a>.
    </p>

    <h2>Transaction Update API</h2>

    <p>
        A transaction object contains the details of what rows should be added, removed and updated.
        The grid API <code>updateRowData(transaction)</code> takes this transaction object
        and applies it to the grid's data.
    </p>

    <p>
        The result of the <code>updateRowData(transaction)</code> is also a transaction, however it is a list
        of <a href="../javascript-grid-row-node/">Row Nodes</a> that got added, removed or updated. Both types
        of transactions look similar, the difference is the data type they contain.
    </p>

    <ul>
        <li><b>Row Data Transaction</b>: Contains Row Data, the data that you are providing to the grid.</li>
        <li><b>Row Node Transaction</b>: Contains Row Nodes, the grid created objects that wrap row data items.</li>
    </ul>

    <p>
        For each data item in a Row Data Transaction there will typically be a Row Node in Row Node Transaction
        wrapping that data item. The only exceptions is for edge cases, for example you tried to delete or update
        a data item that didn't exist.
    </p>

<snippet>
// Grid API method for accepting a transaction
function updateRowData(rowDataTransaction: RowDataTransaction): RowNodeTransaction;

// params for above
interface RowDataTransaction {

    // rows to add
    add?: any[];

    // index for rows to add
    addIndex?: number;

    // rows to remove
    remove?: any[];

    // rows to update
    update?: any[];
}

// result for above
interface RowNodeTransaction {

    // Row Nodes added
    add: RowNode[];

    // Row Nodes removed
    remove: RowNode[];

    // Row Nodes updated
    update: RowNode[];
}</snippet>

    <note>
        The index to add is put in for historical purposes and should not be used. If you want the
        grid to display in a certain order, you should set
        <a href="#delta-row-data">deltaRowDataMode=true</a> and then set
        row data, which will maintain the row order while also working out the update, deletes and
        adds for you.
    </note>

    <h2>Identifying Rows for Update and Remove</h2>

    <p>
        When passing in data to be updated or removed, the grid will be asking:
    </p>
    <p style="margin-left: 10px;">
        <i>"What row do you mean exactly by this data item you are passing?"</i>
    </p>
    <p>
        There are two approaches you can take: 1) Providing Row Node ID's and 2) Using Object References.
    </p>

    <ul>
        <li>

            <h3>Providing Row Node ID's</h3>

            <p>
                Each row inside the grid has a unique ID. As explained in
                <a href="../javascript-grid-row-node/##row-node-ids">Row Node ID's</a> the ID can be generated
                by the grid or it can be provided by the application. If the ID is provided by the application,
                then the grid uses the ID to identify rows for updating and deleting.
            </p>

            <p>
                For updating rows, the grid will find the row with the same ID and then swap the data out for the
                newly provided data.
            </p>

            <p>
                For removing rows, the grid will find the row with the same ID and remove it. For this reason, the
                provided records within the <code>remove</code> array only need to have an ID present.
            </p>
        </li>
        <li>

            <h3>Using Object References</h3>

            <p>
                If you do not provide ID's for the rows, the grid will compare rows using object references.
                In other words when you provide a transaction with update or remove items, the grid will find
                those rows using the '===' operator on the data that your previously provided.
            </p>
            <p>
                When using object references, note the following:
            </p>
            <ol>
                <li>
                    The same instance of the row data items should be used. Using another instance of the same
                    object will stop the grid from making the comparison.
                </li>
                <li>
                    Using object references for identification will be slow for large data sets, as the grid has
                    not way of indexing rows base on object reference.
                </li>
            </ol>

        </li>
    </ul>




    <h2 id="example-updating-with-transaction">Example: Updating with Transaction</h2>

    <p>
        The example uses the <code>updateRowData</code> method in different ways and prints
        the results of the call to the console. The following can be noted:
    </p>

    <ul class="content">
        <li><b>Add Row</b>: Adds a row to the end of the list.</li>
        <li>
            <b>Insert Row @ 2</b>: Inserts a row at position 2 in the list. This works in the grid
            as it doesn't allow sorting, filtering or grouping (all of these would impact the order).
        </li>
        <li>
            <b>Update First 5</b>: Updates the price on the first 5 items in the list (add some items
            first so you have at least 5).
        </li>
        <li>
            <b>Remove Selected</b>: Removes all the selected rows from the list.
        </li>
        <li>
            <b>Get Row Data</b>: Prints all row data in the grid to the console.
        </li>
        <li>
            <b>Clear Data</b>: Sets the data in the grid to an empty list.
        </li>
        <li>
            <b>Add Items</b>: Adds three items.
        </li>
        <li>
            <b>Add Items</b>: Adds three items at position 2.
        </li>
    </ul>

    <?= grid_example('Updating with Transaction', 'updating-with-transaction', 'generated') ?>

    <h2 id="example-updating-with-transaction-and-groups">Example: Updating with Transaction and Groups</h2>

    <p>
        When using transactions and grouping, the groups are kept intact as you add, remove and update
        rows. The example below demonstrates the following:
    </p>
    <ul class="content">
        <li><b>Add For Sale:</b> Adds a new item to 'For Sale' group.</li>
        <li><b>Add In Workshop:</b> Adds a new item to 'In Workshop' group.</li>
        <li><b>Remove Selected:</b> Removes all selected items.</li>
        <li><b>Move to For Sale:</b> Move selected items to 'For Sale' group.</li>
        <li><b>Move to In Workshop:</b> Move selected items to 'In Workshop' group.</li>
        <li><b>Move to Sold:</b> Move selected items to 'Sold' group.</li>
        <li><b>Get Row Data:</b> Prints all row data to the console.</li>
    </ul>
    <p>
        Things to try with the below example include:
    </p>
    <ul class="content">
        <li>Move rows between groups, see how the grid animates the rows to
            the new location with minimal DOM updates.</li>
        <li>Order by 'Created' and then add rows - notice how the new rows
            are always added to the top as they are ordered 'latest first'</li>
    </ul>

    <?= grid_example('Updating with Transaction and Groups', 'updating-with-transaction-and-groups', 'generated', ['enterprise' => true]) ?>

    <h2 id="big-data-small-transactions">Small Changes in Large Grouped Data</h2>

    <p>
        When grouping, the grid will group, sort, filter and aggregate each individual
        group. When there is a lot of data and a lot of groups then this results
        in a lot of computation required for all the data operations.
    </p>
    <p>
        If using <a href="#transactions">transaction updates</a> then the grid does not execute all
        the operations (sort, filter etc) on all the rows. Instead it only re-computes what was
        impacted by a transaction.
    </p>
    <p>
        For example, if items are grouped by city, and a value for each city is summed at the city
        level, then if the value changes for one item, only the aggregation for the city it belongs
        to needs be recomputed. All other groups where data did not change do not need to be recomputed.
    </p>
    <p>
        Deciding what groups need to be operated on again is called Changed Path Selection. After
        the grid applies all adds, removes and updates from a transaction, it works out what groups
        got impacted and only executes the required operations on those groups.
    </p>
    <p>
        Under the hood <a href="#delta-row-data">delta row data</a> uses transactions in the grid,
        so Changed Path Selection applies also when using delta row update.
    </p>

    <p>
        The example below demonstrates Changed Path Selection. The example is best view with the dev
        console open so log messages can be observed. Note the following:
    </p>

    <ul>
        <li>The 'Linux Distro' column is sorted with a custom comparator. The comparator records how many
            times it is called.</li>
        <li>The Value column is aggregated with a custom aggregator. The aggregator records
            how many times it is called.</li>
        <li>When the example first loads, all the data is set into the grid which results in 171 aggregation
            operations (one for each group), 48,131 comparisons (for sorting all rows in each group) and 10,000 filter
            passes (one for each row). The number of milliseconds to complete the operation is also printed (this
            value will depend on your hardware).</li>
        <li>Select a row and click 'Update', 'Delete' OR 'Duplicate' (duplicate results in an add operation).
            Note in the console that the number of aggregations, compares and filters is drastically less.
            The total time to execute is also drastically less</li>
    </ul>

<?= grid_example('Small Changes Big Data', 'small-changes-big-data', 'generated', ['enterprise' => true]) ?>


<?php include '../documentation-main/documentation_footer.php';?>