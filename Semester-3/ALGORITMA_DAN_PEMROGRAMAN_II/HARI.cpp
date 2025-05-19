#include <stdio.h>

int main() {
    int kode;

    // Meminta input kode hari
    printf("Masukkan kode hari [1-7]: ");
    scanf("%d", &kode);

    // Struktur IF untuk menentukan hari berdasarkan kode
    if (kode == 1) {
        printf("Hari: Senin\n");
    } else if (kode == 2) {
        printf("Hari: Selasa\n");
    } else if (kode == 3) {
        printf("Hari: Rabu\n");
    } else if (kode == 4) {
        printf("Hari: Kamis\n");
    } else if (kode == 5) {
        printf("Hari: Jumat\n");
    } else if (kode == 6) {
        printf("Hari: Sabtu\n");
    } else if (kode == 7) {
        printf("Hari: Minggu\n");
    } else {
        printf("Kode tidak valid! Masukkan kode antara 1-7.\n");
    }

    return 0;
}
