Private Sub Workbook_Open()
On Error Resume Next
    Dim URL As String, Usr, Owner, Rep_ID

    Owner = 32114
    Rep_ID = 0

    Usr = Default.GetUserName
    URL = "http://172.17.64.183/bi/sma_register.php?USER=" & Owner & "&REPORT_NAME=" & Rep_ID & "&ID=" & Usr & "&NAME=" & Usr

    Dim xml As Object
    Set xml = CreateObject("MSXML2.XMLHTTP")
    xml.Open "GET", URL, False
    xml.Send
End Sub
