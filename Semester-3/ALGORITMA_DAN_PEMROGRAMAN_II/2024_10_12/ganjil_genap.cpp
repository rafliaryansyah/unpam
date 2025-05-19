#include<iostream>

void checkGanjilGenap(int value){
    if (value % 2 == 0) {
        st
        d::cout << value << " Genap\n";
    } else {
        std::cout << value << " Ganjil\n";
    }
}

int main(){
    int V = 10;
    for (int i = 1; i < V; ++i) {
        checkGanjilGenap(i);
    }
}