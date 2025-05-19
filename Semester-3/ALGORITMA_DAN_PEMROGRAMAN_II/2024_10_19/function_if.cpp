#include<iostream>
using namespace std;
void isCumlaude(float value)    {
    cout << "Result : " << value << endl;
    if (value > 4.0)    {
        cout << "Selamat anda cumlaude " << value << endl;
    } else if (value > 3.0) {
        cout << "Average " << value << endl;
    } else if (value > 2.0) {
        cout << "Kocak " << value << endl;
    } else {
        cout << "Default " << value << endl;
    }
}

int main(){
    float ips[8], averageIpk;
    for (int i = 0; i < 8; ++i) {
        cout << "Masukan IPS Semester Ke - " << i + 1 << " : ";
        cin >> ips[i];
        if (ips[i] > 4.0) {
            cout << "Nilai input gak boleh dari 4.0 = " << ips[i] << endl;
            return 0;
        }
        averageIpk += ips[i];
    }
    isCumlaude(averageIpk / 8);
    return 0;
}