#include"iostream"

using namespace std;

int main(){
    int matriks[3][3];

    for (int i = 0; i < 3; ++i) {
        for (int j = 0; j < 3; ++j) {
            cout << "Input Baris " << i + 1 << " Kolom " << j + 1 << " : ";
            cin >> matriks[i][j];
        }
    }

    for (int i = 0; i < 3; ++i) {
        for (int j = 0; j < 3; ++j) {
            cout << " " << matriks[i][j];
        }
        cout << "\n\t\t  ";
    }
}