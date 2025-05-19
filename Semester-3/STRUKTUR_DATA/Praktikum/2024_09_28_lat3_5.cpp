#include <iostream>
#include <cstring>
using namespace std;

struct Nilai {
    char nim[12];
    char nama[255];
    float nilaiTugas;
    float nilaiUTS;
    float nilaiUAS;
    float nilaiAkhir;
    char nilaiHuruf;
};

void hitungNilaiAkhir(Nilai& student) {

    student.nilaiAkhir = (0.2 * student.nilaiTugas) + (0.35 * student.nilaiUTS) + (0.45 * student.nilaiUAS);

    if (student.nilaiAkhir > 85 && student.nilaiAkhir <= 100) {
        student.nilaiHuruf = 'A';
    } else if (student.nilaiAkhir > 70 && student.nilaiAkhir <= 85) {
        student.nilaiHuruf = 'B';
    } else if (student.nilaiAkhir > 55 && student.nilaiAkhir <= 70) {
        student.nilaiHuruf = 'C';
    } else if (student.nilaiAkhir > 40 && student.nilaiAkhir <= 55) {
        student.nilaiHuruf = 'D';
    } else if (student.nilaiAkhir >= 0 && student.nilaiAkhir <= 40) {
        student.nilaiHuruf = 'E';
    } else {
        student.nilaiHuruf = 'X';
    }
}

int main() {
    Nilai student;
    cout << "Muhammad Rafli Aryansyah - 231011401531\n\n" << endl;
    cout << "Masukkan data mahasiswa:\n";
    cout << "NIM                : ";
    cin.getline(student.nim, 12);

    cout << "Nama               : ";
    cin.getline(student.nama, 255);

    cout << "Nilai Tugas       : ";
    cin >> student.nilaiTugas;

    cout << "Nilai UTS         : ";
    cin >> student.nilaiUTS;

    cout << "Nilai UAS         : ";
    cin >> student.nilaiUAS;

    hitungNilaiAkhir(student);

    cout << "\n\nOutput dari Nilai Input:\n";
    cout << "NIM                : " << student.nim << endl;
    cout << "Nama               : " << student.nama << endl;
    cout << "Nilai Tugas       : " << student.nilaiTugas << endl;
    cout << "Nilai UTS         : " << student.nilaiUTS << endl;
    cout << "Nilai UAS         : " << student.nilaiUAS << endl;
    cout << "Nilai Akhir       : " << student.nilaiAkhir << endl;
    cout << "Nilai Huruf       : " << student.nilaiHuruf << endl;

    return 0;
}
