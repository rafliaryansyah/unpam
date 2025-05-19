#include<iostream>
using namespace std;

struct AddressDetail {
    char road[40];
    char city[15];
    char posscode[6];
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
    int jumlahMahasiswa;

    cout << "Masukkan jumlah mahasiswa: ";
    cin >> jumlahMahasiswa;
    cin.ignore();

    Student students[jumlahMahasiswa];

    for (int i = 0; i < jumlahMahasiswa; i++) {
        cout << "\nMasukkan data mahasiswa ke-" << (i + 1) << ":\n";

        cout << "NIM                : ";
        cin.getline(students[i].nim, 9);

        cout << "Nama Lengkap       : ";
        cin.getline(students[i].fullName, 64);

        cout << "Alamat: \n";
        cout << "  Jalan            : ";
        cin.getline(students[i].address.road, 40);
        cout << "  Kota             : ";
        cin.getline(students[i].address.city, 15);
        cout << "  Kode POS         : ";
        cin.getline(students[i].address.posscode, 5);

        cout << "Tanggal Lahir: \n";
        cout << "  Tanggal (dd)     : ";
        cin >> students[i].dateOfBirth.date;
        cout << "  Bulan (mm)       : ";
        cin >> students[i].dateOfBirth.month;
        cout << "  Tahun (yyyy)     : ";
        cin >> students[i].dateOfBirth.year;
        cin.ignore();
    }

    cout << "\n\nOutput dari Nilai Input: \n\n";
    cout << "Muhammad Rafli Aryansyah - 231011401531" << endl;
    for (int i = 0; i < jumlahMahasiswa; i++) {
        cout << "Mahasiswa ke-" << (i + 1) << ":\n";
        cout << "NIM                : " << students[i].nim << endl;
        cout << "Nama Lengkap       : " << students[i].fullName << endl;
        cout << "Alamat: \n";
        cout << "  Jalan            : " << students[i].address.road << endl;
        cout << "  Kota             : " << students[i].address.city << endl;
        cout << "  Kode POS         : " << students[i].address.posscode << endl;

        cout << "Tanggal Lahir      : "
             << students[i].dateOfBirth.date << "-"
             << students[i].dateOfBirth.month << "-"
             << students[i].dateOfBirth.year << endl;
        cout << "------------------------\n";
    }

    return 0;
}
