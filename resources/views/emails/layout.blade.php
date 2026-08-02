<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subject', site_name())</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f9; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f9; padding:30px 15px;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0" style="max-width:560px; width:100%;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#0f172a,#1e3a8a); border-radius:16px 16px 0 0; padding:32px 36px 24px; text-align:center;">
                            <img src="{{ logo_url() }}" alt="{{ site_name() }}" style="height: 40px; max-width: 320px; width: auto; object-fit: contain; background:#fff; padding:6px 14px; border-radius:12px; margin-bottom:14px;">
                            <h1 style="color:#fff; font-size:22px; font-weight:700; margin:0; letter-spacing:-0.3px;">@yield('heading', site_name())</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#fff; padding:36px; border-radius:0 0 16px 16px; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
                            @yield('content')
                            <hr style="border:none; border-top:1px solid #e2e8f0; margin:24px 0;">
                            <p style="margin:0; font-size:13px; color:#94a3b8; text-align:center;">
                                {{ site_name() }}<br>
                                Need help? Contact our support team.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
