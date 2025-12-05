<style>
    input {
        width: 300px;
        height: 50px;
        margin-top: 50px;
        border: solid 2px blue;
        border-radius: 5%;
    }

    form {
        display: flex;
        justify-content: center;
    }

    table {
        width: 60%;
        margin: 30px auto;
        border-collapse: collapse;
        text-align: left;
        font-family: Arial, sans-serif;
    }

    th,
    td {
        border: 1px solid #ccc;
        padding: 12px;
    }

    th {
        background-color: #007BFF;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .no-data {
        text-align: center;
        color: #777;
        font-style: italic;
    }
</style>

<form action="">
    <input id="search" name="search" type="text" placeholder="Search">
</form>

<table id="resultTable">
    <thead>
        <tr>
            <th>Имя</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody id="tableBody">
        <tr>
            <td colspan="2" class="no-data">Введите запрос для поиска...</td>
        </tr>
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.1.js"
    integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        fetchQuery();
        $("#search").keyup(function() {
            let query = $("#search").val();

            fetchQuery(query)
        });

        function fetchQuery(query = null) {
            $.ajax({
                url: "{{ route('search.ajax') }}",
                type: 'GET',
                data: {
                    search: query
                },
                dataType: 'JSON',
                success: function(data) {
                    let tableBody = $("#tableBody");
                    tableBody.empty();

                    if (data.length > 0) {
                        data.forEach(function(item) {
                            tableBody.append(`
                                <tr>
                                    <td>${item.name}</td>
                                    <td>${item.email}</td>
                                </tr>
                            `);
                        });
                    } else {
                        tableBody.append(`<tr><td colspan="2" class="no-data">Ничего не найдено</td></tr>`);
                    }
                },
                error: function() {
                    $("#tableBody").html(`<tr><td colspan="2" class="no-data">Ошибка запроса к серверу</td></tr>`);
                }
            });
        }
    });
</script>