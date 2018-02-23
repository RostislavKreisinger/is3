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
}

function manageData(data) {
    addButtons(data['endpoints']);
    sendRequest('/debug/differences', 'GET', {}, manageDifferences);
}

function manageDifferences(differences) {
    console.log(differences);
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

function addDifference(data) {
    sendRequest('/debug/differences/add', 'GET', data);
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
    console.log(result);
}

function refresh() {
    sendRequest('/debug/presta/debug-data', 'GET', {}, manageData);
}