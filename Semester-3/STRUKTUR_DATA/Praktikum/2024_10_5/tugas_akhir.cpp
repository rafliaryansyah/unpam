#include<iostream>

void Swap(int *a, int *b){
    int temp = *a;
    *a = *b;
    *b = temp;
}

int main(){
    std::cout << "MUHAMMAD RAFLI ARYANSYAH - 231011401531" << std::endl;
    int x = 5, y = 10;
    std::cout << "Sebelum Dipindahkan : x = " << x << ", y = " << y << std::endl;
    Swap(&x, &y);
    std::cout << "Setelah Dipindahkan : x = " << x << ", y = " << y << std::endl;
    return 0;
}