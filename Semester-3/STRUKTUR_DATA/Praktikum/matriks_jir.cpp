//
// Created by Rafli on 21/09/24.
//


#include <iostream>
using namespace std;

int main() {
    int A[3][4] = {
            {1, 3, 4, 5},
            {2, 4, 6, 8},
            {3, 5, 7, 9}
    };

    cout << "Elemen-elemen Matriks A:" << endl;
    for (int i = 0; i < 3; i++) {
        for (int j = 0; j < 4; j++) {
            cout << A[i][j] << " ";
        }
        cout << endl;
    }
    cout << "Muhammad Rafli Aryansyah - 231011401531" << endl;
    return 0;
}