#include<iostream>
#include<stdlib.h>

using namespace std;
typedef struct node *simpul;
struct node
{
    char Isi;
    simpul Next;
};

//=====================
//==Prototype Function=
//=====================
void Sisip_Depan (simpul &L, char elemen);
void Sisip_Belakang (simpul &L, char elemen);
void Sisip_Tengah1 (simpul &L, char elemen1, char elemen2);
void Sisip_Tengah2 (simpul &L, char elemen1, char elemen2);
void Hapus_Depan (simpul &L);
void Hapus_Belakang (simpul &L);
void Hapus_Tengah (simpul &L, char elemen);
void Cetak (simpul L);

//=====================
//==Function Main==
//=====================
int main()
{
    char huruf, huruf2;
    simpul L = NULL; // Pastikan Bahwa L kosong
    cout << "==OPERASI PADA SINGLE LINKED LIST==" << endl << endl;

    //==================
    //==Sisip Depan==
    //==================
    cout << "Penyisipan Simpul Di Depan" << endl << endl;
    cout << "Masukan Huruf : "; cin >> huruf;
    Sisip_Depan(L, huruf);
    cout << "Masukan Huruf : "; cin >> huruf;
    Sisip_Depan(L, huruf);
    cout << "Masukan Huruf : "; cin >> huruf;
    Sisip_Depan(L, huruf);
    cout << "Masukan Huruf : "; cin >> huruf;
    Sisip_Depan(L, huruf);
    cout << "Masukan Huruf : "; cin >> huruf;
    Sisip_Depan(L, huruf);
    Cetak(L);

    //==================
    //==Sisip Belakang==
    //==================
    cout << "\n\nPenyisipan Simpul Di Belakang" << endl << endl;
    cout << "Masukan Huruf : "; cin >> huruf;
    Sisip_Belakang(L, huruf);
    cout << "Masukan Huruf : "; cin >> huruf;
    Sisip_Belakang(L, huruf);
    cout << "Masukan Huruf : "; cin >> huruf;
    Sisip_Belakang(L, huruf);
    cout << "Masukan Huruf : "; cin >> huruf;
    Sisip_Belakang(L, huruf);
    Cetak(L);

    //==================
    //==Sisip Simpul Setelah Simpul Tertentu==
    //==================
    cout << endl << endl << "Masukan Huruf : "; cin >> huruf;
    cout << "Disisip Setelah Huruf : "; cin >> huruf2;
    cout << "Disisip Setelah " << huruf2 << endl;
    Sisip_Tengah2(L, huruf, huruf2);
    Cetak(L);

    //==================
    //==Hapus Simpul Depan==
    //==================
    cout << endl << endl << "Setelah Hapus Simpul Depan" << endl;
    Hapus_Depan(L);
    Cetak(L);

    //==================
    //==Hapus Simpul Belakang==
    //==================
    cout << endl << endl << "Setelah Hapus Simpul Belakang" << endl;
    Hapus_Belakang(L);
    Cetak(L);

    //==================
    //==Hapus Simpul TENGAH==
    //==================
    cout << "\n\nMasukan Huruf Tengah Yang akan dihapus : "; cin >> huruf;
    Hapus_Tengah(L, huruf);
    Cetak(L);
}

//==============================
//==FUNCTION HAPUS SIMPUL DEPAN==
//==============================
void Hapus_Depan (simpul &L)
{
    simpul Hapus;
    if (L == NULL)
        cout << "Linked List Kosong............";
    else
    {
        Hapus = L;
        L = L->Next;
        Hapus->Next = NULL;
        free(Hapus);
    }
}

//==============================
//==FUNCTION HAPUS SIMPUL BELAKANG==
//==============================
void Hapus_Belakang (simpul &L)
{
    simpul bantu, hapus;
    if (L == NULL)
        cout << "Linked List Kosong............";
    else
    {
        bantu = L;
        while (bantu->Next->Next != NULL) bantu = bantu->Next;
        hapus = bantu->Next;
        bantu->Next = NULL;
        free(hapus);
    }
}

//==============================
//==FUNCTION HAPUS SIMPUL DI TENGAH==
//==============================
void Hapus_Tengah (simpul &L, char elemen)
{
    simpul bantu, hapus;
    if (L == NULL)
        cout << "Linked List Kosong............";
    else
    {
        bantu = L;
        while (bantu->Next->Isi != elemen) bantu = bantu->Next;
        hapus = bantu->Next;
        bantu->Next = hapus->Next;
        hapus->Next = NULL;
        free(hapus);
    }
}

//==============================
//==FUNCTION SISIP SIMPUL DEPAN==
//==============================
void Sisip_Depan (simpul &L, char elemen)
{
    simpul baru; // new simpul
    baru = (simpul) malloc(sizeof(simpul));
    baru->Isi = elemen;
    baru->Next = NULL;
    if (L == NULL)
        L = baru;
    else
    {
        baru->Next = L;
        L = baru;
    }
}

//==============================
//==FUNCTION SISIP SIMPUL SETELAH SIMPUL TERTENTU==
//==============================
void Sisip_Tengah1 (simpul &L, char elemen1, char elemen2)
{
    simpul bantu, baru;
    baru = (simpul) malloc(sizeof(simpul));
    baru->Isi = elemen1;
    baru->Next = NULL;
    if (L == NULL)
        cout << "List Kosong............" << endl;
    else
    {
        bantu = L;
        while (bantu->Isi != elemen2) bantu = bantu->Next;
        baru->Next = bantu->Next;
        bantu->Next = baru;
    }
}

//==============================
//==FUNCTION SISIP SIMPUL BELAKANG==
//==============================
void Sisip_Belakang (simpul &L, char elemen)
{
    simpul bantu, baru;
    baru = (simpul) malloc(sizeof(simpul));
    baru->Isi = elemen;
    baru->Next = NULL;
    if (L == NULL)
        L = baru;
    else
    {
        bantu = L;
        while (bantu->Next != NULL) bantu = bantu->Next;
        bantu->Next = baru;
    }
}

//==============================
//==FUNCTION MENCETAK ISI LINKED LIST==
//==============================
void Cetak (simpul L)
{
    simpul bantu;
    if (L == NULL)
        cout << "Linked List Kosong ........" << endl;
    else
    {
        bantu = L;
        while (bantu != NULL)
        {
            cout << bantu->Isi << " ";
            bantu = bantu->Next;
        }
        cout << endl;
    }
}
