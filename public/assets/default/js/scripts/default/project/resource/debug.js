window.onload = function () {
    refresh();
};

function sendRequest(url, method, data, callback) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === XMLHttpRequest.DONE && callback) {
            var response = this.responseText;

            try {
                response = JSON.parse(response);
            } finally {
                callback(response);
            }
        }
    };

    if (!data['endpoint']) {
        data['endpoint'] = document.getElementById('endpoint-input').value;
    }

    if (method === 'GET' && Object.keys(data).length > 0) {
        var first = true;

        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                if (first) {
                    url += '?';
                    first = false;
                } else {
                    url += '&';
                }

                url += key + '=' + data[key];
            }
        }
    }

    xhttp.open(method, location.href + url, true);
    xhttp.send();
}

function testCall() {
    var endpoint = document.getElementById('endpoint-input').value;
    var paramLabels = document.querySelectorAll('#param-controls label');
    var params = [];
    params['active'] = document.getElementById('use-active-cb').checked ? 1 : 0;
    params['inactive'] = document.getElementById('use-inactive-cb').checked ? 1 : 0;
    params['endpoint'] = endpoint;
    [].forEach.call(paramLabels, function (label) {
        if (label.style.display !== 'none') {
            var control = label.querySelector('input');

            if (control.value) {
                params[control.name] = control.value;
            }
        }
    });
    sendRequest('/debug/presta', 'GET', params, showResult);
}

function selectEndpoint(endpoint) {
    event.preventDefault();
    var focusedButtons = document.querySelectorAll('#endpoint-buttons .focus');
    [].forEach.call(focusedButtons, function (button) {
        button.classList.remove('focus');
    });
    document.getElementById('button-' + endpoint).classList.add('focus');
    document.getElementById('endpoint-input').value = endpoint;
    sendRequest('/debug/presta/select-endpoint', 'GET', {'endpoint':  endpoint}, showControls);
    loadDifferences();
}

function manageData(data) {
    addButtons(data['endpoints']);
}

function loadDifferences() {
    var data = {deleted: document.getElementById('show-deleted-cb').checked ? 1 : 0};
    sendRequest('/debug/differences', 'GET', data, manageDifferences);
}

function manageDifferences(differences) {
    populateSelects('', differences);
    formatOperators(document.getElementById('table-join-select'), differences['join']);
    var columnTable = document.getElementById('colsTable');
    columnTable.innerHTML = '';
    var tablesTable = document.getElementById('tablesTable');
    tablesTable.innerHTML = '';
    var conditionsTable = document.getElementById('conditionsTable');
    conditionsTable.innerHTML = '';
    differences['differences'].forEach(function (difference) {
        var activationCode = '<input type="checkbox" class="checkbox hover-hand" ' + (difference['active'] ? 'title="Deactivate" onchange="deactivateDifference(' + difference['id'] + ')" checked' : 'title="Activate" onchange="activateDifference(' + difference['id'] + ')"') + '>';
        var differenceCode = '<tr><td>' + difference['difference'] + '</td>';
        var deleteCode = '<i class="fa hover-hand ' + (difference['deleted_at'] ? 'fa-recycle" title="Restore" onclick="restoreDifference(' + difference['id'] + ')"' : 'fa-trash" title="Delete" onclick="deleteDifference(' + difference['id'] + ')"') + '></i>';

        if (difference['type'] === 2) {
            differenceCode += '<td><i class="fa fa-plus hover-hand" title="Add condition" onclick="showJoinConditionForm(' + difference['id'] + ')"></i></td>';
        }

        differenceCode += '<td>' + activationCode + '</td><td>' + deleteCode + '</td></tr>';

        if (difference['type'] === 2) {
            differenceCode += getJoinConditionForm(difference['id']);
        }

        switch (difference['type']) {
            case 1:
                columnTable.innerHTML += differenceCode;
                break;
            case 2:
                tablesTable.innerHTML += differenceCode;
                break;
            case 3:
                conditionsTable.innerHTML += differenceCode;
                break;
        }

        if (difference['type'] === 2) {
            populateSelects(difference['id'], differences);
        }
    });
}

function populateSelects(id, differences) {
    formatOperators(document.getElementById('condition-operator-select' + id), differences['operator']);
    formatOperators(document.getElementById('condition-compare-operator-select' + id), differences['secondOperator']);
}

function formatOperators(element, data) {
    element.innerHTML = '';
    data.forEach(function (operator) {
        element.innerHTML += '<option>' + operator + '</option>';
    });
}

function addButtons(endpoints) {
    if (Object.keys(endpoints).length > 0) {
        document.getElementById('endpoint-buttons').innerHTML = '';
    }

    for (var index in endpoints) {
        if (endpoints.hasOwnProperty(index)) {
            document.getElementById('endpoint-buttons').innerHTML += '<li><button id="button-' + index + '" class="btn btn-default" onclick="selectEndpoint(\'' + index + '\')">' + endpoints[index] + '</button></li>';
        }
    }

    selectEndpoint('orders');
}

function addColumn() {
    var data = [];
    data['type'] = 'column';

    if (document.getElementById('column-raw-input').value) {
        data['raw'] = document.getElementById('column-raw-input').value;
    } else {
        data['column'] = document.getElementById('column-name-input').value;
        data['alias'] = document.getElementById('column-alias-input').value;
    }

    addDifference(data);
}

function addTable() {
    var data = [];
    data['type'] = 'table';
    data['join-type'] = document.getElementById('table-join-select').value;
    data['table'] = document.getElementById('table-name-input').value;
    data['alias'] = document.getElementById('table-alias-input').value;

    addDifference(data);
}

function addCondition(id) {
    var data = [];
    var type = 'condition';

    if (id) {
        data['id'] = id;
        type = 'join-' + type;
    }

    data['type'] = type;


    if (document.getElementById('condition-raw-input' + id).value) {
        data['raw'] = document.getElementById('condition-raw-input' + id).value;
    } else {
        data['operator'] = document.getElementById('condition-operator-select' + id).value;
        data['first'] = document.getElementById('condition-first-input' + id).value;
        data['compare-operator'] = document.getElementById('condition-compare-operator-select' + id).value;
        data['second'] = document.getElementById('condition-second-input' + id).value;
    }

    addDifference(data);
}

function addDifference(data) {
    sendRequest('/debug/differences/add', 'GET', data, manageDifferences);
}

function activateDifference(id) {
    if (confirm('Do you really want to activate this difference?')) {
        sendRequest('/debug/differences/activate', 'GET', {'id': id}, manageDifferences);
    }
}

function deactivateDifference(id) {
    if (confirm('Do you really want to deactivate this difference?')) {
        sendRequest('/debug/differences/deactivate', 'GET', {'id': id}, manageDifferences);
    }
}

function restoreDifference(id) {
    if (confirm('Do you really want to restore this difference?')) {
        sendRequest('/debug/differences/restore', 'GET', {'id': id}, manageDifferences);
    }
}

function deleteDifference(id) {
    if (confirm('Do you really want to remove this difference?')) {
        sendRequest('/debug/differences/delete', 'GET', {'id': id}, manageDifferences);
    }
}

function showControls(controlsList) {
    var paramControls = document.querySelectorAll('#param-controls label');
    [].forEach.call(paramControls, function (control) {
        control.style.display = 'none';
    });

    controlsList.forEach(function (controlName) {
        if (controlName === 'dates') {
            document.getElementById('date-from-label').style.display = 'inline-block';
            document.getElementById('date-to-label').style.display = 'inline-block';
        }

        if (controlName === 'ids') {
            document.getElementById('since-id-label').style.display = 'inline-block';
            document.getElementById('max-id-label').style.display = 'inline-block';
        }

        if (controlName === 'id') {
            document.getElementById('only-id-label').style.display = 'inline-block';
        }

        if (controlName === 'foreign') {
            document.getElementById('only-foreign-label').style.display = 'inline-block';
        }
    });
}

function showResult(result) {
    document.getElementById('result-block').innerHTML = result;
}

function refresh() {
    sendRequest('/debug/presta/debug-data', 'GET', {}, manageData);
    setDefaultFilters();
}

function columnInputManager() {
    var columnInput = document.getElementById('column-name-input');
    var aliasInput = document.getElementById('column-alias-input');
    var rawInput = document.getElementById('column-raw-input');

    rawInput.disabled = columnInput.value.length > 0 || aliasInput.value.length > 0;
    columnInput.disabled = aliasInput.disabled = rawInput.value.length > 0;
}

function conditionInputManager(id) {
    var operatorSelect = document.getElementById('condition-operator-select' + id);
    var firstInput = document.getElementById('condition-first-input' + id);
    var compareOperatorSelect = document.getElementById('condition-compare-operator-select' + id);
    var secondInput = document.getElementById('condition-second-input' + id);
    var rawInput = document.getElementById('condition-raw-input' + id);

    rawInput.disabled = firstInput.value.length > 0 || secondInput.value.length > 0;
    firstInput.disabled = secondInput.disabled = operatorSelect.disabled = compareOperatorSelect.disabled = rawInput.value.length > 0;
}

function getJoinConditionForm(id) {
    return '<tr id="add-condition-difference' + id + '" style="display: none;">' +
        '<td colspan="4">' +
        '<label for="condition-operator-select' + id + '">Operator: ' +
        '<select class="form-control" id="condition-operator-select' + id + '" name="operator"></select>' +
        '</label>' +
        '<label for="condition-first-input' + id + '">First: ' +
        '<input class="form-control" id="condition-first-input' + id + '" name="first" onchange="conditionInputManager(' + id + ')">' +
        '</label>' +
        '<label for="condition-compare-operator-select' + id + '">Compare operator: ' +
        '<select class="form-control" id="condition-compare-operator-select' + id + '" name="compare-operator"></select>' +
        '</label>' +
        '<label for="condition-second-input' + id + '">Second: ' +
        '<input class="form-control" id="condition-second-input' + id + '" name="second" onchange="conditionInputManager(' + id + ')">' +
        '</label>' +
        '<label for="condition-raw-input' + id + '">Raw condition text: ' +
        '<input class="form-control" id="condition-raw-input' + id + '" name="raw" onchange="conditionInputManager(' + id + ')">' +
        '</label>' +
        '<input class="form-control" value="ADD" type="button" onclick="addCondition(' + id + ')">' +
        '</td>' +
        '</tr>';
}

function showJoinConditionForm(id) {
    var conditionForm = document.getElementById('add-condition-difference' + id);

    if (conditionForm.style.display === 'table-row') {
        conditionForm.style.display = 'none';
    } else {
        conditionForm.style.display = 'table-row';
    }
}

function setDefaultFilters() {
    var today = new Date();
    var twoDaysAgo = new Date();
    twoDaysAgo.setDate(today.getDate() - 2);
    document.getElementById('date-from-input').value = twoDaysAgo.toISOString().split('T', 1)[0] + 'T00:00';
    document.getElementById('date-to-input').value = today.toISOString().split('T', 1)[0] + 'T23:59';
}