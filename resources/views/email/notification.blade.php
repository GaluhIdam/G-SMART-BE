<html>
<head>
    <style>
        td {
            padding-right: 20px;
            padding-left: 10px;
        }
 
        .container {
            width: 350px;
            height: 450px;
            background-color: #fafafa;
        }
 
        .btn {
            display: inline-block;
            background-color: #0C82B9;
            color: #FFFFFF;
            padding: 8px 13px;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            opacity: 0.9;
            border-radius: 5px;
        }
 
        .header {
            background-color: #005885; 
            padding: 5px 20px; 
            color: #fff;
        }
 
        .body {
            margin-left: 20px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
 
        .gsmart {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>New Hanggar Slot Request</h3>
        </div>
        <div class="body">
            <p>Hi {{ $data['body']['user_name'] }}, <br>{{ $data['body']['message'] }}</p>
            <table style="margin: 20px 0;">
                <tr>
                    <td>AMS Name</td>
                    <td>: {{ $data['body']['ams_name'] }}</td>
                </tr>
                @if ($data['type'] == 2)
                    <tr>
                        <td>Hangar</td>
                        <td>: {{ $data['body']['hangar'] }}</td>
                    </tr>
                    <tr>
                        <td>Line</td>
                        <td>: {{ $data['body']['line'] }}</td>
                    </tr>
                @endif
                <tr>
                    <td>Registration</td>
                    <td>: {{ $data['body']['ac_reg'] }}</td>
                </tr>
                @if ($data['type'] == 1)
                    <tr>
                        <td>Customer</td>
                        <td>: {{ $data['body']['customer'] }}</td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td>: {{ $data['body']['type'] }}</td>
                    </tr>
                    <tr>
                        <td>Level</td>
                        <td>: {{ $data['body']['level'] }}</td>
                    </tr>
                    <tr>
                        <td>Progress</td>
                        <td>: {{ $data['body']['progress'] }}%</td>
                    </tr>
                @endif
                <tr>
                    <td>TAT</td>
                    <td>: {{ $data['body']['tat'] }}</td>
                </tr>
                <tr>
                    <td>Start Date</td>
                    <td>: {{ $data['body']['start_date'] }}</td>
                </tr>
                <tr>
                    <td>End Date</td>
                    <td>: {{ $data['body']['end_date'] }}</td>
                </tr>
            </table>
        </div>
        <div class="gsmart">
            <a href="{{ $data['body']['link'] }}" class="btn" style="color: #fff;">Open GSMART</a>
        </div>
    </div>
</body>
</html>