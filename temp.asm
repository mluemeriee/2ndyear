section .data
    msg1 db "Enter temperature value: "
    len1 equ $ - msg1

    msg2 db "Convert to (1) Celsius or (2) Fahrenheit? "
    len2 equ $ - msg2

    resultMsg db "Result: "
    len3 equ $ - resultMsg

    newline db 10

section .bss
    input resb 16
    choice resb 2
    output resb 16

section .text
    global _start

_start:

    ; ---- PRINT msg1 ----
    mov rax, 1
    mov rdi, 1
    mov rsi, msg1
    mov rdx, len1
    syscall

    ; ---- READ INPUT ----
    mov rax, 0
    mov rdi, 0
    mov rsi, input
    mov rdx, 16
    syscall

    ; ---- CONVERT INPUT STRING TO INT ----
    mov rsi, input
    call atoi
    mov rbx, rax   ; store number

    ; ---- PRINT msg2 ----
    mov rax, 1
    mov rdi, 1
    mov rsi, msg2
    mov rdx, len2
    syscall

    ; ---- READ CHOICE ----
    mov rax, 0
    mov rdi, 0
    mov rsi, choice
    mov rdx, 2
    syscall

    mov rax, rbx

    ; ---- CHECK CHOICE ----
    cmp byte [choice], '2'
    je c_to_f

f_to_c:
    ; (F - 32) * 5 / 9
    sub rax, 32
    imul rax, 5
    mov rcx, 9
    xor rdx, rdx
    div rcx
    jmp print_result

c_to_f:
    ; (C * 9 / 5) + 32
    imul rax, 9
    mov rcx, 5
    xor rdx, rdx
    div rcx
    add rax, 32

print_result:
    ; ---- PRINT "Result: " ----
    mov rbx, rax   ; save result

    mov rax, 1
    mov rdi, 1
    mov rsi, resultMsg
    mov rdx, len3
    syscall

    ; ---- CONVERT NUMBER TO STRING ----
    mov rax, rbx
    mov rdi, output + 15   ; start from end
    call itoa

    ; rsi = start of number string
    mov rax, 1
    mov rdi, 1
    mov rdx, rbx   ; length returned in rbx
    syscall

    ; ---- PRINT NEWLINE ----
    mov rax, 1
    mov rdi, 1
    mov rsi, newline
    mov rdx, 1
    syscall

    ; ---- EXIT ----
    mov rax, 60
    xor rdi, rdi
    syscall

; ==========================
; ASCII TO INTEGER (atoi)
; ==========================
atoi:
    xor rax, rax

atoi_loop:
    movzx rcx, byte [rsi]
    cmp rcx, 10        ; newline
    je atoi_done
    cmp rcx, 0
    je atoi_done

    sub rcx, '0'
    imul rax, 10
    add rax, rcx

    inc rsi
    jmp atoi_loop

atoi_done:
    ret

; ==========================
; INTEGER TO ASCII (itoa)
; ==========================
itoa:
    mov rcx, 10
    mov rbx, 0        ; digit count

itoa_loop:
    xor rdx, rdx
    div rcx
    add rdx, '0'

    dec rdi
    mov [rdi], dl
    inc rbx

    test rax, rax
    jnz itoa_loop

    mov rsi, rdi      ; pointer to string
    ret
