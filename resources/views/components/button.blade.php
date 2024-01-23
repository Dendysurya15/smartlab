<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn bg-emerald-500 hover:bg-emerald-600 text-white py-2
    px-4
    rounded']) }}>
    {{ $slot }}
</button>