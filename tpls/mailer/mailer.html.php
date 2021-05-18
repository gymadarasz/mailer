<form method="POST" action="{$__base}mailer">
    <input type="hidden" name="csrf" value="{$__csrf}" />
    
    <label>To email address</label>
    <input class="form-control"  type="email" name="to_email" value="{@$to_email}" placeholder="To email address" required />
    <br />

    <label>From email address</label>
    <input class="form-control"  type="email" name="from_email" value="{@$from_email}" placeholder="From email address" required />
    <br />
    
    <label>From displayed name</label>
    <input class="form-control"  type="text" name="from_name" value="{@$from_name}" placeholder="From displayed name" required />
    <br />
    
    <label>Subject</label>
    <input class="form-control"  type="text" name="subject" value="{@$subject}" placeholder="Subject" required />
    <br />
    
    <label>Body</label>
    <textarea class="form-control"  name="body" placeholder="Body" rows="12" required>{@$body}</textarea>
    <br />
    
    <button class="btn btn-primary">Send</button>
</form>