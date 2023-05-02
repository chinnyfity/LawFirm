@include('emails.email_header')
<table role="presentation" border="0" cellpadding="0" cellspacing="10px" style="padding: 0px 20px 30px 20px;">
  <tr>
    <td>
      <h2>Hi {{ $data['firstname'] ? ucfirst($data['firstname']) : 'Chief' }},</h2>
      <p style="margin-top:-30px!important;color:#111;padding:0!important">
        {!! nl2br(e(strip_tags($data['message']))) !!}
      </p>
    </td> 
  </tr>
</table>
@include('emails.email_footer')