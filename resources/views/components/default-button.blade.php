<button {{ $attributes->merge(['type' => 'submit', 'class' => 'mb-1 mt-1 me-1 btn btn-default']) }}>
    {{ $slot }}
</button>