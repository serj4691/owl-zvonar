<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" value="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="text-center" style="margin: 20px 0px 20px 0px;">
            <a href="http://zvonar/admin/base_phones" >Список клиентов</a><br>
            <span class="text-secondary">Laravel 6 Import Export Excel </span>
        </div>
        <br />

        <div class="clearfix">
            <div class="float-left">
                <form class="form-inline" action="{{url('admin/base_phone/import')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="form-group">
                    <div class="custom-file" >
                        <input type="file" class="custom-file-input" name="imported_file" id="imported_file" />
                        <label class="custom-file-label" for="imported_file"></label>
                       
                    </div>
                </div>
                <button style="margin-left: 10px;" class="btn btn-info" type="submit">Import</button>
            </form>
               
            </div>


        </div>
    </div>
</body>

</html>
