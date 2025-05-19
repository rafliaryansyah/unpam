#include <iostream>
#include <iomanip>

using namespace std;

int main() {
    int value[20], i, j, k, N;
    int temp;
    bool swap;
    cout << "\nMuhammad Rafli ARYANSYAH - 231011401531\n\n" << endl;
    cout << "Masukan Banyak Bilangan : ";
    cin >> N;

    for (i = 0; i < N; ++i) {
        cout << "Element Ke - " << i << " : ";
        cin >> value[i];
    }

    // Proses cetak sebelum diurutkan
    cout << "Nilai sebelum diurutkan : ";
    for (i = 0; i < N; ++i) {
        cout << setw(3) << value[i];
    }

    // Proses pengurutan
    i = 0;
    swap = true;

    while ((i <= N - 2) && swap) {
        swap = false;
        for (j = N - 1; j >= i + 1; --j) {
            if (value[j] < value[j - 1]) {
                temp = value[j];
                value[j] = value[j - 1];
                value[j - 1] = temp;
                swap = true;
            }
            cout << "\n Untuk J = " << j << " : ";
            for (k = 0; k < N; ++k) {
                cout << setw(3) << value[k];
            }
        }
        i++; // Increment after inner loop
    }

    // Proses cetak setelah diurutkan
    cout << "\n Data setelah diurutkan : ";
    for (i = 0; i < N; ++i) {
        cout << setw(3) << value[i];
    }
}
