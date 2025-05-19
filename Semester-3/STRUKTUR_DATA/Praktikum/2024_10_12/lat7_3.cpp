#include <iostream>
#include <iomanip>
using namespace std;

void Cetak(int data[], int n) {
    for (int i = 0; i < n; i++) {
        cout << setw(3) << data[i];
    }
    cout << "\n";
}

int Partisi(int data[], int p, int r) {
    int pivot = data[r];
    int i = p - 1;

    for (int j = p; j < r; j++) {
        if (data[j] <= pivot) {
            i++;
            swap(data[i], data[j]);
        }
    }
    swap(data[i + 1], data[r]);
    return i + 1;
}

void Quick_Sort(int data[], int p, int r) {
    if (p < r) {
        int q = Partisi(data, p, r);
        Quick_Sort(data, p, q - 1);
        Quick_Sort(data, q + 1, r);
    }
}

int main() {
    int Nilai[20];
    int N;

    cout << "Masukkan Banyak Bilangan: ";
    cin >> N;

    for (int i = 0; i < N; i++) {
        cout << "Elemen ke-" << i << ": ";
        cin >> Nilai[i];
    }

    cout << "\nData Sebelum diurut: ";
    Cetak(Nilai, N);
    cout << endl;

    Quick_Sort(Nilai, 0, N - 1);

    cout << "\nData Setelah diurut: ";
    Cetak(Nilai, N);

    return 0;
}
