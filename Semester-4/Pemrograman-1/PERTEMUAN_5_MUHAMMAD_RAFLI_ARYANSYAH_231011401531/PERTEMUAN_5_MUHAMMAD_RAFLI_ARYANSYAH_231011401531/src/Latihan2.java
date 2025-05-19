import java.util.Scanner;

public class Latihan2 {
    public static void main(String[] args) {
        int inputan = 0;
        System.out.println("PERTEMUAN 5 - MUHAMMAD RAFLI ARYANSYAH - 231011401531 - LATIHAN 2");
        Scanner input = new Scanner(System.in);
        System.out.print("Silahkan masukkan input nomor : ");
        inputan = input.nextInt();
        switch (inputan) {
            case 1:
                System.out.println("ANDA PILIH NO. 1");
                break;
            case 2:
                System.out.println("ANDA PILIH NO. 2");
                break;
            default:
                System.out.println("OK DEFAULT INI.");
                break;
        }
    }
}
