<!DOCTYPE html>
<html>
<head>
    <title>Ticketto.hu</title>
</head>
<body style="background-color:#edf2f7;text-align: center">
<div style="width:570px;background:#edf2f7;margin:auto;padding:50px 0;">
    <div style="width:100%;background-color:#ffffff;min-height:50vh;display:table;vertical-align:center;padding:0 50px">
        <table style="padding:30px 0" align="center" cellpadding="0" cellspacing="0" role="presentation">
            <tbody>
            <tr>
                <td style="text-align: left;">
                    <h1>{{ $details['title'] }}</h1>
                    </br>

                    <p>Ön regisztrált egy eseményre ideiglenes felhasználóként. Létrejött egy ideiglenes hozzáférés
                        melyet az
                        alábbi link alatt érhet el:</p>
                    </br>

                    <a href="{{ $details['url'] }}" rel="noopener"
                       style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';border-radius:4px;color:#fff;display:inline-block;overflow:hidden;text-decoration:none;background-color:#2d3748;border-bottom:8px solid #2d3748;border-left:18px solid #2d3748;border-right:18px solid #2d3748;border-top:8px solid #2d3748"
                       target="_blank" data-saferedirecturl="https://www.google.com/url?q={{ $details['url'] }}">Játéktér
                        megnyitása</a>
                    </br>
                    </br>
                    </br>
                    </br>
                    <p>Köszönjük, Ticketto</p>

                </td>
            </tr>
            </tr>
            </tbody>

        </table>
    </div>
</div>

</body>
</html>
