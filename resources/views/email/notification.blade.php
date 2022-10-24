<html>
<head>
    <style>
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
        td {
            padding-right: 20px;
        }
    </style>
</head>
<body>
    <p>Hi {{ $data['body']['user_name'] }}, <br>{{ $data['body']['message'] }}</p>
    <table style="margin: 20px 0;">
        <tr>
            <td>AMS Name</td>
            <td>: {{ $data['body']['ams_name'] }}</td>
        </tr>
        <tr>
            <td>Registration</td>
            <td>: {{ $data['body']['ac_reg'] }}</td>
        </tr>
        @if ($data['body']['type'] != 3)
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
    <a href="{{ $data['body']['link'] }}" class="btn" style="color: #fff;">Open GSMART</a>
</body>
</html>