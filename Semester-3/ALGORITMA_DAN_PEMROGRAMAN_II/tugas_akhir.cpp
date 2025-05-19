#include <iostream>
using namespace std;

int main() {
    const float pi = 3.14;  // Nilai konstanta pi
    float r, volume, luas;

    // Meminta input jari-jari
    cout << "Masukkan jari-jari bola: ";
    cin >> r;

    // Menghitung volume bola
    volume = (4.0 / 3.0) * pi * r * r * r;

    // Menghitung luas permukaan bola
    luas = 4 * pi * r * r;

    // Menampilkan hasil
    cout << "Volume bola: " << volume << endl;
    cout << "Luas permukaan bola: " << luas << endl;

    return 0;
}
