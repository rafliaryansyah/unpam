#include<iostream>
using namespace std;

struct AddressDetail {
    char road[40];
    char city[15];
    char posscode[5];
};

struct DateOfBirth {
    int date;
    int month;
    int year;
};

struct Student {
    char nim[9];
    char fullName[64];
    AddressDetail address;
    DateOfBirth dateOfBirth;
};

int main() {
    Student student;

    // Input
    cout << "Masukkan data mahasiswa:\n";

    cout << "NIM                : ";
    cin.getline(student.nim, 9);

    cout << "Nama Lengkap       : ";
    cin.getline(student.fullName, 64);

    cout << "Alamat: \n";
    cout << "  Jalan            : ";
    cin.getline(student.address.road, 40);
    cout << "  Kota             : ";
    cin.getline(student.address.city, 15);
    cout << "  Kode POS         : ";
    cin.getline(student.address.posscode, 5);

    cout << "Tanggal Lahir: \n";
    cout << "  Tanggal (dd)     : ";
    cin >> student.dateOfBirth.date;
    cout << "  Bulan (mm)       : ";
    cin >> student.dateOfBirth.month;
    cout << "  Tahun (yyyy)     : ";
    cin >> student.dateOfBirth.year;

    // Output
    cout << "\n\nOutput dari Nilai Input: \n\n";

    cout << "NIM                : " << student.nim << endl;
    cout << "Nama Lengkap       : " << student.fullName << endl;
    cout << "Alamat: \n";
    cout << "  Jalan            : " << student.address.road << endl;
    cout << "  Kota             : " << student.address.city << endl;
    cout << "  Kode POS         : " << student.address.posscode << endl;

    cout << "Tanggal Lahir      : "
         << student.dateOfBirth.date << "-"
         << student.dateOfBirth.month << "-"
         << student.dateOfBirth.year << endl;

    return 0;
}