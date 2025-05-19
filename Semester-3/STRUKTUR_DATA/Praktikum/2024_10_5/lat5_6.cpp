#include<iostream>
using namespace std;

struct Student {
    char nim[9];
    char fullName[255];
    char address[255];
    int age;
};

void Read(Student &student){
    cout << "NIM : ";
    cin >> student.nim;

    cout << "Nama Lengkap : ";
    cin >> student.fullName;

    cout << "Alamat Lengkap : ";
    cin >> student.address;



    cout << "Umur : ";
    cin >> student.age;
};
void Result(Student &student){
    cout << "NIM : " << student.nim << "\n";

    cout << "Nama Lengkap : " << student.fullName << "\n";

    cout << "Alamat Lengkap : " << student.address << "\n";
//    c, f, tugas_akhir
    cout << "Umur : " << student.age << "\n";
};

int main(){
    Student student;
    cout << "\nMuhammad Rafli ARYANSYAH - 231011401531\n\n" << endl;
    cout << "Membaca Nilai Anggota Struktur \n";
    Read(student);
    cout << "Mencetak Nilai Anggota Struktur \n";
    Result(student);

}