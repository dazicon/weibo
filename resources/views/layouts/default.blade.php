<!DOCTYPE html>
<html>
  <head>
    <title>Weibo App</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body>
    @include('layouts._head')

    <div class="container">
      <div class="col-md-offset-1 col-md-10">
      @yield('content')
      @include('layouts._footer')
      </div>
    </div>
  </body>
</html>
