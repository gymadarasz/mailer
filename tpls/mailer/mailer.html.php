<form method="POST" action="{$__base}mailer">
    <input type="hidden" name="csrf" value="{$__csrf}" />
    
    <input class="form-control"  type="email" name="to_email" value="" placeholder="To email address" required />
    <br />

    <input class="form-control"  type="email" name="from_email" value="" placeholder="From email address" required />
    <br />
    
    <input class="form-control"  type="text" name="from_name" value="" placeholder="From displayed name" required />
    <br />
    
    <input class="form-control"  type="text" name="subject" value="" placeholder="Subject" required />
    <br />
    
    <textarea class="form-control"  name="body" placeholder="Body" rows="12" required></textarea>
    <br />
    
    <button class="btn btn-primary">Send</button>
</form>