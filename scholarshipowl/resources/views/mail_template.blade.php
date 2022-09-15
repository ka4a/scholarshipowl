<!DOCTYPE html>
<html>
<head></head>
<body style="padding:0; margin:0">

    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"  align="center"  style="padding:0; text-align:center; width:100%;">
        <thead bgcolor="#f1f5f8">
            <tr>
                <th align="center" valign="top" style="background-color:#f1f5f8; padding-bottom:0;">
                    <table width="554" height="153" cellpadding="0" cellspacing="0" border="0" align="center" align="center" style="padding:0; width:554px; height:153px">                        <thead>
                            <tr>
                                <th>
									<img height="133" width="550" src="{{ url('/common/img/logo-mail.jpg') }}" />
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td height="20" cellpadding="0" cellspacing="0" border="0" valign="bottom" bgcolor="#ffffff" style="-webkit-text-adjust:none; line-height:0.5; font-size:5px; height="20px" ">
                                    <img height="20" width="554" src="{{ url('/common/img/top.png') }}" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </th>
            </tr>

        </thead>
        <tbody>
            <tr>
                <td bgcolor="#D8E8FF" align="center">
                    <table width="554" height="100%" cellpadding="0" cellspacing="0" halign="center" align="center" bgcolor="#ffffff" style="text-align:left; background-color:#ffffff;">
                        <tbody>

                            <tr>
                                <td width="1" bgcolor="#cad9ef" style="-webkit-text-adjust:none; width:1px; font-size:1px; line-height:1px;"></td>
                                <td width="25">&nbsp;</td>
                                <td width="" style="color:#75797B; line-height:1.46; font-family:  Arial, Helvetica, sans-serif;">
                                    {{ $content }}
                                </td>
                                <td width="25">&nbsp;</td>
                                <td width="1" bgcolor="#cad9ef" style="-webkit-text-adjust:none; width:1px; font-size:1px; line-height:1px;"></td>
                            </tr>
                            <tr>
                                <td width="1" bgcolor="#cad9ef" style="-webkit-text-adjust:none; width:1px; font-size:1px; line-height:1px;"></td>
                                <td width="25">&nbsp;</td>
                                <td height="65">&nbsp;</td>
                                <td width="25">&nbsp;</td>
                                <td width="1" bgcolor="#cad9ef" style="-webkit-text-adjust:none; width:1px; font-size:1px; line-height:1px;"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <td bgcolor="#5191ED" align="center">
                    <table width="554" height="130" cellpadding="0" cellspacing="0" halign="top" align="center">
                        <thead>
                            <tr>
                                <th colspan="3" height="22" style="-webkit-text-adjust:none; line-height:0.5; font-size:5px; background-color:#5191ed; padding-top: 0;">
                                    <img height="22" width="554" src="{{ url('/common/img/bottom.png') }}" />
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="33%" align="center" height="90" valign="middle">
                                    <a href="{{ url('') }}" target="_blank">
                                        <img height="64" width="179" src="{{ url('/common/img/scholarshipowl-button.png') }}" />
                                    </a>
                                </td>
                                <td width="34%" align="center" valign="middle">
                                    <a href="https://www.facebook.com/scholarshipowl" target="_blank">
                                        <img height="64" width="179" src="{{ url('/common/img/facebook-button.png') }}" />
                                    </a>
                                </td>
                                <td width="33%" align="center" valign="middle">
                                    <a href="{{ url('/contact') }}" target="_blank">
                                        <img height="64" width="179" src="{{ url('/common/img/contactus-button.png') }}" />
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center" height="100" style="color:#D8E8FF;">
                                    <p style="-webkit-text-adjust:none; font-size:12px; font-family: Verdana; line-height:1;">
                                        Unable to see this message? <a href="#" target="_blank" style="color:#D8E8FF;">Click here to view</a>.
                                    </p>
                                    <p style="-webkit-text-adjust:none; font-size:12px; font-family: Verdana; line-height:1;">
                                        <a href="#" target="_blank" style="color:#D8E8FF;">Unsubscribe</a> from all Scholarship emails.
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
