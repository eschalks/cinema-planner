@component('components.dialog', ['id' => 'register'])
    <form method="post" action="{{ route('login') }}">
        {{ csrf_field() }}
        <h2>Looks like you're new around here</h2>
        <p>
            What's your name?
        </p>
        <input name="name" required>
    </form>
@endcomponent
