#include <iostream>
using namespace std;
int main() {
    int count;

    cout << "Panjang max nomor : ";
    cin >> count;


    if (count <= 0) {
        cout << "Masukan nilai positif." << std::endl;
        return 1;
    }

    long long a = 0, b = 1;

    cout << "Bilangan Fibonnaci: ";
    for (int i = 0; i < count; ++i) {
        cout << a << " ";

        long long next = a + b;
        a = b;
        b = next;
    }

    cout << endl;
    return 0;
}
