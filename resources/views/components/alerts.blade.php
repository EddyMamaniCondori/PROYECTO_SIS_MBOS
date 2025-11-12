@if (session()->has('success') || session()->has('error') || session()->has('info'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            @foreach (['success', 'error', 'info'] as $msg)
                @if (session($msg))
                    Toast.fire({
                        icon: "{{ $msg }}",
                        title: "{{ session($msg) }}"
                    });
                @endif
            @endforeach
        });
    </script>
@endif
