import Plugin from 'src/plugin-system/plugin.class';
import Axios from 'axios';
import Swal from 'sweetalert2';

export default class DataTablePlugin extends Plugin {
    static options = {
        'tableSelector': '.dataTable-table',
        'tableSearchColumnsRowSelector': '.dataTable-searchColumns',
        'paginationButtonSelector': '.dataTable-pagination a'
    };

    init() {
        this.entityName = this.el.getAttribute('data-table-entity');
        this.table = this.el.querySelector(this.options.tableSelector);
        this.tableHeader = this.table.querySelector('thead');
        this.tableBody = this.table.querySelector('tbody');
        this.tableSearchColumnsRow = this.el.querySelector(this.options.tableSearchColumnsRowSelector);
        this.editController = this.el.getAttribute('data-table-edit-controller');

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

    registerPaginationEvents() {
        this.el.querySelectorAll(this.options.paginationButtonSelector).forEach((paginationButton) => {
            paginationButton.addEventListener('click', (e) => {
                e.preventDefault();
                let pageNo = paginationButton.getAttribute('data-page-no');
                this.el.setAttribute('data-table-page', pageNo);
                this.loadData();
            });
        });
    }

    readTableHeaderColumns() {
        let tableHeaderColumns = this.tableHeader.querySelectorAll('th');
        let columns = [];
        tableHeaderColumns.forEach((tableHeaderColumn) => {
            let name = tableHeaderColumn.getAttribute('data-col-name');
            let type = tableHeaderColumn.getAttribute('data-col-type');
            let format = tableHeaderColumn.getAttribute('data-col-format');
            let sortable = tableHeaderColumn.getAttribute('data-col-sortable');
            let searchable = tableHeaderColumn.getAttribute('data-col-searchable');
            let dataclass = tableHeaderColumn.getAttribute('data-col-dataclass');

            columns.push({'name': name, 'type': type, 'format': format, 'sortable': sortable, 'searchable': searchable, 'dataclass': dataclass});
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

                let value = this.getValue(columnConfig.name, row, columnConfig);

                if (columnConfig.name == 'actions') {
                    value = '<a href="' + this.editController + '/' + row.id + '" class="btn btn-sm btn-primary">Edit</a>';
                } 

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

    getValue(fieldName, row, config) {
        let fieldNames = fieldName.split('.');

        if (fieldNames.length == 1) {
            return this.getRowValue(fieldName, row, config);
        }

        for(let i = 0; i < fieldNames.length; i++) {
            let values = row[fieldNames[i]];
            if (typeof values === 'object') {
                let newFieldName = fieldNames.slice(1).join('.');
                return this.getValue(newFieldName, values, config);
            }
        }
    }

    getRowValue(fieldName, row, config) {
        if (row.length > 0) {
            let values = [];
            for (let i = 0; i < row.length; i++) {
                if (row[i].hasOwnProperty(fieldName)) {
                    values.push(row[i][fieldName]);
                }
            }
                
            if (config.dataclass === null) {
                return values.join(', ');
            } else {
                let html = '';
                values.forEach((value) => {
                    html += '<span class="' + config.dataclass + '">' + value + '</span>';
                });
                return html;
            }
        } 

        return row[fieldName] ?? '';
    }

    loadData(limit) {

        window.spinner = true;

        limit = limit ?? parseInt(this.el.getAttribute('data-table-limit') ?? 25)
        let page = parseInt(this.el.getAttribute('data-table-page') ?? 1) 
        let searchArray = this.buildSearchArray();

        Axios.post('/api/search/' + this.entityName, {
            page: page,
            limit: limit,
            orderBy: null,
            search: searchArray
        })
        .then((response) => {
            this.drawPagination(limit, response.data.totalCount, page);
            this.drawTableBody(response.data.data);
            this.registerPaginationEvents();
            window.spinner = false;
        })
        .catch((error) => {
            Swal.fire({
                icon: 'error',
                text: error
            });
            window.spinner = false;
        });
    }

    drawPagination(limit, total, currentPage) {
        let totalPages = Math.ceil(total / limit);
        let prevPageNo = currentPage - 1;
        let nextPageNo = currentPage + 1;

        let paginationEl = this.el.querySelector('.dataTable-pagination');
        paginationEl.innerHTML = '';

        let paginationUl = document.createElement('ul');
        paginationUl.classList.add('pagination');

        if (prevPageNo > 0) {
            let paginationLiPrev = document.createElement('li');
            paginationLiPrev.classList.add('page-item');
            paginationLiPrev.innerHTML = '<a class="page-link" data-page-no="' + prevPageNo + '" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
            paginationUl.appendChild(paginationLiPrev);
        }

        for (let i = 1; i <= totalPages; i++) {
            let paginationLi = document.createElement('li');
            paginationLi.classList.add('page-item');
            if (i === currentPage) {
                paginationLi.classList.add('active');
            }
            paginationLi.innerHTML = '<a class="page-link" href="#" data-page-no="' + i + '">' + i + '</a>';
            paginationUl.appendChild(paginationLi);
        }

        if (currentPage < totalPages) {
            let paginationLiNext = document.createElement('li');
            paginationLiNext.classList.add('page-item');
            paginationLiNext.innerHTML = '<a class="page-link" data-page-no="' + nextPageNo + '" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
            paginationUl.appendChild(paginationLiNext);
        }

        paginationEl.appendChild(paginationUl);
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
