@include('emails.components.email-header', [
    'page_title' => $page_title,
    'header_description' => $header_description
])

<h1>{{ $content_title }}</h1>

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
    <tbody class="mcnDividerBlockOuter">
        <tr>
            <td class="mcnDividerBlockInner" style="min-width: 100%; padding: 10px 18px;">
                <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%"
                    style="min-width: 100%;border-top-width: 2px;border-top-style: solid;border-top-color: #EAEAEA;">
                    <tbody>
                        <tr>
                            <td>
                                <span></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<p style="padding-bottom:5px; margin: 10px 20px; line-height: 25px;">{!! $content_description !!}</p>

@include('emails.components.email-footer')
