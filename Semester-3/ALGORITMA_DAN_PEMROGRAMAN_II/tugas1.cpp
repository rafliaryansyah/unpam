//
// Created by Rafli on 20/09/24.
//
// MUHAMMAD RAFLI ARYANSYAH
// 231011401531
// ALGORITMA DAN PEMROGRAMAN II

#include <iostream>
#include <vector>
#include <string>

using namespace std;

struct Group {
    string groupName;
    vector<string> members;
};

int main() {
    Group group;
    int numMembers;

    // Input nama grup
    cout << "Masukkan nama grup: ";
    getline(cin, group.groupName);

    // Input jumlah anggota
    cout << "Masukkan jumlah anggota: ";
    cin >> numMembers;
    cin.ignore();

    // Input nama-nama anggota
    for (int i = 0; i < numMembers; ++i) {
        string memberName;
        cout << "Masukkan nama anggota ke-" << i + 1 << ": ";
        getline(cin, memberName);
        group.members.push_back(memberName);
    }

    // Output hasil
    cout << "\nNama grup: " << group.groupName << "\n";
    cout << "Anggota: ";
    for (size_t i = 0; i < group.members.size(); ++i) {
        cout << group.members[i];
        if (i < group.members.size() - 1) {
            cout << ", ";
        }
    }
    cout << endl;

    return 0;
}
