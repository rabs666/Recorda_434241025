@if (session('success') || session('error') || $errors->any())
<style>
    .flash-message { margin: 0 0 16px; padding: 12px 16px; border-radius: 10px; font-size: 14px; }
    .flash-success { background: #e7f6ec; color: #1d7a44; border: 1px solid #b6e3c6; }
    .flash-error { background: #fdecea; color: #b3261e; border: 1px solid #f5c2bd; }
</style>
@endif
@if (session('success'))
    <div class="flash-message flash-success" role="status">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="flash-message flash-error" role="alert">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="flash-message flash-error" role="alert">
        <ul style="margin:0; padding-left:18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
