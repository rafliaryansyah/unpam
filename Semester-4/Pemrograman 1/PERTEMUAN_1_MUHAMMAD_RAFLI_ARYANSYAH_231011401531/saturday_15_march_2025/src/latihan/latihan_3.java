package latihan;
import java.util.Scanner;
public class latihan_3 {
    public static void main(String[] args) {
        // MUHAMMAD RAFLI ARYANSYAH - 231011401531
        Scanner input = new Scanner(System.in);
        // Input nama, NIM, dan nilai
        System.out.print("Masukkan Nama: ");
        String nama = input.nextLine();
        System.out.print("Masukkan NIM: ");
        String nim = input.nextLine();
        System.out.print("Masukkan Nilai: ");
        int nilai = input.nextInt();
        // Menentukan kelulusan berdasarkan nilai
        String hasil;
        if (nilai >= 60) {
            hasil = "Lulus";
        } else {
            hasil = "Tidak Lulus";
        }
        // Menampilkan hasil
        System.out.println("\nHasil:");
        System.out.println("Nama: " + nama);
        System.out.println("NIM: " + nim);
        System.out.println("Nilai: " + nilai);
        System.out.println("Status Kelulusan: " + hasil);

        input.close();
    }
}