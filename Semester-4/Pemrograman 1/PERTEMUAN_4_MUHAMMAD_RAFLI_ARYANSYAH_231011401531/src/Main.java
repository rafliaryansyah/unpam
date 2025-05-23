import java.util.Scanner;
public class Main {
    public static void main(String[] args) {
        int kehadiran = 0;
        int tugas = 0;
        int uts = 0;
        int uas = 0;

        int realisasi = 14;

        int bobotKehadiran = 0;
        int bobotTugas = 0;
        int bobotUts = 0;
        int bobotUas = 0;

        int total = 0;

        Scanner input = new Scanner(System.in);

        System.out.print("Masuk kehadiran \n");
        System.out.print("Masuk Tugas \n");
        System.out.print("Masuk UTS \n");
        System.out.print("Masuk UAS \n");

        kehadiran = input.nextInt();
        tugas = input.nextInt();
        uts = input.nextInt();
        uas = input.nextInt();

        bobotKehadiran = kehadiran / realisasi * 10;
        bobotTugas = tugas * 20 / 100;
        bobotUts = uts * 30 / 100;
        bobotUas = uas * 40 / 100;

        total = bobotKehadiran + bobotTugas + bobotUts + bobotUas;
        
        System.out.print("\n");

        System.out.println("Kehadiran : " + kehadiran + " Kali ");
        System.out.println("Tugas : " + tugas);
        System.out.println("UTS : " + uts);
        System.out.println("UAS : " + uas);
        System.out.println("Total : " + total);

    }
}
