
<!DOCTYPE html>
<html lang="en" xml:lang="en">
<head>
    <title>Sharreit</title>
</head>
<body>
    <input type="text" value="{{ url('/') }}" id="txtsite_url" style="display:none">
    <p style="font-size:16px">Redirecting...</p>

    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <script>
        var site_urls = $('#txtsite_url').val()+"/";
        setTimeout(idleLogout, 20);
        $(document).on('mouseover mousedown touchstart click keydown mousewheel DDMouseScroll wheel scroll',document, function(e){
            setTimeout(idleLogout, 20);
        });

    function idleLogout(){
        window.location.href = site_urls + 'redirect1';
    }
    </script>
</body>
</html>