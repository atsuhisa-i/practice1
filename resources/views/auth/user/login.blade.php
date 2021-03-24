<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <nav>
        <div>Sample</div>
        <div>
            <a href="#">ログイン</a>
            <a href="#">新規登録</a>
        </div>
    </nav>
    <main>
        <div>
            <div>ログイン</div>
            <div>
                <form action="{{route('user.login')}}" method="POST">
                    @csrf
                    <div>
                        <label for="email">E-mail</label>
                        <input type="text" id="email" name="email">
                    </div>
                    <div>
                        <label for="password">Password</label>
                        <input type="text" id="password" name="password">
                    </div>
                    <button type="submit">ログイン</button>
                </form>
            </div>
        </div>
    </main>
    
</body>
</html>