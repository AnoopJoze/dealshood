<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>You're Offline — DealsHood</title>
    <style>
    *{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
         background:#f8fafc;display:flex;align-items:center;justify-content:center;
         min-height:100vh;padding:24px;text-align:center;}
    .card{background:#fff;border-radius:20px;border:1px solid #e2e8f0;
          padding:48px 32px;max-width:380px;width:100%;}
    .icon{font-size:3rem;margin-bottom:20px;}
    h1{font-size:1.4rem;font-weight:800;color:#0f172a;margin-bottom:8px;}
    p{font-size:.9rem;color:#64748b;line-height:1.7;margin-bottom:24px;}
    button{padding:12px 28px;background:#0f172a;color:#fff;border:none;
           border-radius:100px;font-size:.88rem;font-weight:700;cursor:pointer;}
    button:hover{background:#0f3f7e;}
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">📡</div>
        <h1>You're offline</h1>
        <p>It looks like you lost your internet connection. Check your connection and try again.</p>
        <button onclick="window.location.reload()">Try Again</button>
    </div>
</body>
</html>