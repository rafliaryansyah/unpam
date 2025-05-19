#include <iostream>
#include <queue>
using namespace std;

int main() {
    queue<char> antrian;
    char pilihan;
    char item;

    do {
        cout << "Muhammad Rafli Aryansyah - 231011401531" << endl;
        cout << "\nMenu Antrian:\n";
        cout << "1. INSERT\n";
        cout << "2. DELETE\n";
        cout << "3. CETAK ANTRIAN\n";
        cout << "4. QUIT\n";
        cout << "Masukkan pilihan (1-4): ";
        cin >> pilihan;

        switch(pilihan) {
            case '1':
                cout << "Masukkan karakter yang akan dimasukkan ke antrian: ";
                cin >> item;
                antrian.push(item);
                cout << item << " dimasukkan ke antrian.\n";
                break;

            case '2':
                if (!antrian.empty()) {
                    cout << "Karakter " << antrian.front() << " dikeluarkan dari antrian.\n";
                    antrian.pop();
                } else {
                    cout << "Antrian kosong. Tidak ada yang bisa dihapus.\n";
                }
                break;

            case '3':
                if (!antrian.empty()) {
                    cout << "Isi antrian: ";
                    queue<char> temp = antrian;
                    while (!temp.empty()) {
                        cout << temp.front() << " ";
                        temp.pop();
                    }
                    cout << "\n";
                } else {
                    cout << "Antrian kosong.\n";
                }
                break;

            case '4':
                cout << "Keluar dari program.\n";
                break;

            default:
                cout << "Pilihan tidak valid. Silakan coba lagi.\n";
        }
    } while(pilihan != '4');

    return 0;
}
