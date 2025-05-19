#include<iostream>

using namespace std;

int main(){
    int MAX_LENGTH_AND_MAX_LOOP;

    cout << "Input max size of array : ";
//    if (!( cin >> MAX_LENGTH_AND_MAX_LOOP)){ return cout << "Wrong input data type.";}
    cin >> MAX_LENGTH_AND_MAX_LOOP;

    int value[MAX_LENGTH_AND_MAX_LOOP];
    int total = 0;
    float average;

    for (int i = 0; i < MAX_LENGTH_AND_MAX_LOOP; ++i) {
        cout << "Element input at " << i + 1 << " = ";
        cin >> value[i];
        total += value[i];
    }

    average = (float) total / MAX_LENGTH_AND_MAX_LOOP;

    cout << "\n\n Number sequence = ";
    for (int i = 0; i < MAX_LENGTH_AND_MAX_LOOP; ++i) {
        cout << value[i] << " ";
    }

    cout << "\n Total Sequence = " << total;
    cout << "\n Average Sequence = " << average;
}