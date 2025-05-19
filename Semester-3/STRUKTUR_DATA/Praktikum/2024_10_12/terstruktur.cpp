#include <iostream>
#include <string>

using namespace std;

struct Student {
    string name;
    string nim;
    string gender;
    float dataStructureScore;
    Student* next;
};

Student* head = nullptr;

void insertData() {
    Student* newStudent = new Student();

    cout << "Enter Name: ";
    cin.ignore();
    getline(cin, newStudent->name);

    cout << "Enter NIM: ";
    getline(cin, newStudent->nim);

    cout << "Enter Gender: ";
    getline(cin, newStudent->gender);

    cout << "Enter Data Structure Score: ";
    cin >> newStudent->dataStructureScore;

    newStudent->next = nullptr;

    if (head == nullptr) {
        head = newStudent;
    } else {
        Student* temp = head;
        while (temp->next != nullptr) {
            temp = temp->next;
        }
        temp->next = newStudent;
    }
    cout << "Data has been inserted successfully!" << endl;
}

void deleteData() {
    if (head == nullptr) {
        cout << "No data to delete!" << endl;
        return;
    }

    if (head->next == nullptr) {
        delete head;
        head = nullptr;
    } else {
        Student* temp = head;
        while (temp->next->next != nullptr) {
            temp = temp->next;
        }
        delete temp->next;
        temp->next = nullptr;
    }
    cout << "Last data has been deleted successfully!" << endl;
}

void printData() {
    if (head == nullptr) {
        cout << "No data available!" << endl;
        return;
    }

    Student* temp = head;
    while (temp != nullptr) {
        cout << "Name: " << temp->name << endl;
        cout << "NIM: " << temp->nim << endl;
        cout << "Gender: " << temp->gender << endl;
        cout << "Data Structure Score: " << temp->dataStructureScore << endl;
        cout << "--------------------------" << endl;
        temp = temp->next;
    }
}

void showMenu() {
    int choice;
    do {
        cout << "LIN. SINGLY LINKED LIST" << endl;
        cout << "==========================" << endl;
        cout << "1. INSERT DATA" << endl;
        cout << "2. DELETE DATA" << endl;
        cout << "3. PRINT DATA" << endl;
        cout << "4. EXIT" << endl;
        cout << "Choose (1-4): ";
        cin >> choice;

        switch (choice) {
            case 1:
                insertData();
                break;
            case 2:
                deleteData();
                break;
            case 3:
                printData();
                break;
            case 4:
                cout << "Exiting program..." << endl;
                break;
            default:
                cout << "Invalid choice. Please try again!" << endl;
                break;
        }

        cout << endl;
    } while (choice != 4);
}

int main() {
    showMenu();
    return 0;
}
