<form name="upForm" id="upForm" action="{{url param.action=import-logistic}}" method="post" enctype="multipart/form-data"  target="ifrmSubmit">
    <input type="hidden" name="submit" value="submit" />
    <input type="hidden" name="logistic_code" value="{{$logisticCode}}" />
    <input type="file" name="logistic" />
    <input type="submit" value="导入">
</form>