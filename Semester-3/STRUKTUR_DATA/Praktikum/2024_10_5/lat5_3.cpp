//
// Created by Rafli on 05/10/24.
//
// result -5


#include<iostream>
using namespace std;

int max(int x, int y, int z) {
    int max = x;
    if (y > max) {
        max = y;
    }
    if (z > max) {
        max = z;
    }
    return (max);
}

int min(int x, int y, int z) {
    int min = x;
    if (y < min) {
        min = y;
    }
    if (z < min) {
        min = z;
    }
    return (min);
}

//int middle(int x, int y, int z) {
//    int mid = x;
//    if (mid < )
//}

int main(){
    int a, b, c;
    cout << "\nMuhammad Rafli ARYANSYAH - 231011401531\n\n" << endl;
    cout << "Masukan Nilai A : ";
    cin >> a;

    cout << "Masukan Nilai B : ";
    cin >> b;

    cout << "Masukan Nilai C : ";
    cin >> c;

    cout << "\n\nNilai A = " << a << "\n";
    cout << "Nilai B = " << b << "\n";
    cout << "Nilai C = " << c << "\n";
    cout << "\n\nNilai Terbesar : " << max(a, b, c) << "\n";
    cout << "Nilai Terkecil : " << min(a, b, c) << "\n\n";
}