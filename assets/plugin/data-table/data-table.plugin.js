import Plugin from 'src/plugin-system/plugin.class';
import axios from 'axios';

export default class DataTablePlugin extends Plugin {
    static options = {
        'tableSelector': '.dataTable-table',
        'tableSearchColumnsRowSelector': '.dataTable-searchColumns'

    };

    init() {
        this.entityName = this.el.getAttribute('data-table-entity');
        this.table = this.el.querySelector(this.options.tableSelector);
        this.tableHeader = this.table.querySelector('thead');
        this.tableBody = this.table.querySelector('tbody');
        this.tableSearchColumnsRow = this.el.querySelector(this.options.tableSearchColumnsRowSelector);

        this.registerEvents();

        this.loadData();
    }

    registerEvents() {
        var that = this;
        this.tableSearchColumnsRow.addEventListener('keypress', function(e) {
            let keynum = e.keyCode||e.which;
            if (keynum === 13) {
                that.loadData();
            }
        });
    }

    readTableHeaderColumns() {
        let tableHeaderColumns = this.tableHeader.querySelectorAll('th');
        let columns = [];
        tableHeaderColumns.forEach((tableHeaderColumn) => {
            let name = tableHeaderColumn.getAttribute('data-col-name');
            let type = tableHeaderColumn.getAttribute('data-col-type');
            let format = tableHeaderColumn.getAttribute('data-col-format');

            columns.push({'name': name, 'type': type, 'format': format});
        });
        return columns;
    }

    drawTableBody(dataRows) {
        let tableBody = document.createElement('tbody');
        let columns = this.readTableHeaderColumns();

        dataRows.forEach((row) => {

            let tableBodyRow = document.createElement('tr');

            columns.forEach((columnConfig) => {
                let tableBodyColumn = document.createElement('td');

                let value = row[columnConfig.name] ?? '';

                tableBodyColumn.innerHTML = value;
                tableBodyRow.appendChild(tableBodyColumn);
            });

            if (row.id !== null) {
                tableBodyRow.setAttribute('data-id', row.id);
            }

            tableBody.appendChild(tableBodyRow);

        });
        this.tableBody.innerHTML = tableBody.innerHTML;
    }

    loadData() {

        let searchArray = this.buildSearchArray();
        window.spinner = true;

        axios.post('/api/search/' + this.entityName, {
            page: 1,
            limit: 25,
            orderBy: null,
            search: searchArray
        })
        .then((response) => {
            this.drawTableBody(response.data.data);
            window.spinner = false;
        })
        .catch((error) => {
            console.log(error);
        });
    }

    buildSearchArray() {
        let search = [];
        let searchColumns = this.tableSearchColumnsRow.querySelectorAll('input');
        searchColumns.forEach((searchColumn) => {
            let searchColumnValue = searchColumn.value;
            if (searchColumnValue !== '') {
                let searchColumnField = searchColumn.getAttribute('data-col-name');
                let searchColumnOperator = searchColumn.getAttribute('data-col-operator') ?? 'contains';
                search.push({'operator': searchColumnOperator, 'field': searchColumnField, 'value': searchColumnValue});
            }
        });
        return search;
    }

}
