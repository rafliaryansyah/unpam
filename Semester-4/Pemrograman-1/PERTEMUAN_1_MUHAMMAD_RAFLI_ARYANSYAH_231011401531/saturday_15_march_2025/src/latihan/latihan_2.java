package latihan;
import java.util.Scanner;

// MUHAMMAD RAFLI ARYANSYAH - 231011401531 - PERTEMUAN 2 (TUGAS TERSTRUKTUR) - SABTU, 15 MARET 2025
public class latihan_2 {
    public static void main(String[] args) {
        Scanner scan = new Scanner(System.in); System.out.print("Masukkan Jarak (meter): "); int inputJarak = scan.nextInt();

        System.out.print("Masukkan Waktu (detik): ");
        int inputWaktu = scan.nextInt();

        if (inputWaktu != 0) {
            double kecepatan = (double) inputJarak / inputWaktu;
            System.out.println("Kecepatan: " + kecepatan + " m/s");
            System.out.print("MUHAMMAD RAFLI ARYANSYAH - 231011401531 - PERTEMUAN 2 (TUGAS TERSTRUKTUR) - SABTU, 15 MARET 2025");
        } else {
            System.out.println("Waktu tidak boleh nol!");
        }

        scan.close();
    }
}
