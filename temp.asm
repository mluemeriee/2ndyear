section .data
    msg1 db "Enter temperature value: ", 0
    msg2 db "Convert to (1) Celsius or (2) Fahrenheit? ", 0
    resultMsg db "Result: ", 0
    newline db 10, 0

section .bss
    input resb 10
    choice resb 2
    num resq 1

section .text
    global _start

_start:

    ; Print msg1
    mov rax, 1
    mov rdi, 1
    mov rsi, msg1
    mov rdx, 30
    syscall

    ; Read input
    mov rax, 0
    mov rdi, 0
    mov rsi, input
    mov rdx, 10
    syscall

    ; Convert ASCII to number (simple)
    mov rsi, input
    call atoi
    mov [num], rax

    ; Print msg2
    mov rax, 1
    mov rdi, 1
    mov rsi, msg2
    mov rdx, 45
    syscall

    ; Read choice
    mov rax, 0
    mov rdi, 0
    mov rsi, choice
    mov rdx, 2
    syscall

    mov rax, [num]

    ; Check choice
    cmp byte [choice], '2'
    je c_to_f

f_to_c:
    ; (F - 32) * 5 / 9
    sub rax, 32
    imul rax, 5
    mov rbx, 9
    xor rdx, rdx
    div rbx
    jmp print

c_to_f:
    ; (C * 9 / 5) + 32
    imul rax, 9
    mov rbx, 5
    xor rdx, rdx
    div rbx
    add rax, 32

print:
    ; Print result message
    mov rbx, rax
    mov rax, 1
    mov rdi, 1
    mov rsi, resultMsg
    mov rdx, 8
    syscall

    ; Convert number to string
    mov rax, rbx
    call itoa

    ; Print newline
    mov rax, 1
    mov rdi, 1
    mov rsi, newline
    mov rdx, 1
    syscall

    ; Exit
    mov rax, 60
    xor rdi, rdi
    syscall

; ------------------------
; Convert string to int
atoi:
    xor rax, rax
atoi_loop:
    movzx rbx, byte [rsi]
    cmp rbx, 10
    je atoi_done
    sub rbx, '0'
    imul rax, 10
    add rax, rbx
    inc rsi
    jmp atoi_loop
atoi_done:
    ret

; ------------------------
; Convert int to string
itoa:
    mov rcx, 10
    mov rbx, 0
    mov rdi, input + 9

itoa_loop:
    xor rdx, rdx
    div rcx
    add rdx, '0'
    dec rdi
    mov [rdi], dl
    inc rbx
    test rax, rax
    jnz itoa_loop

    ; Print number
    mov rax, 1
    mov rdi, 1
    mov rsi, rdi
    mov rdx, rbx
    syscall
    ret
