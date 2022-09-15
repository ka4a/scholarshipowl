<template>
  <transition name="modal-fade">
    <div v-if="visible" class="popup">
      <div
        class="background"
        @click="closeModal"
      />

      <div
        class="content"
        @click.stop=""
      >
        <button
          class="close-btn"
          @click="closeModal"
        >
          <img src="~/assets/img/close_cross.svg" alt="close">
        </button>

        <h3 class="modal-title">Sunrise – Terms of Use</h3>

        <div class="text">
          <p>Last updated: September 2018</p>

          <p>
            These Terms of Use contains the terms and conditions that govern your
            access to and use of the Services (as defined below) to be provided to
            you by Apply Me Inc., d/b/a scholarship.app (“Sunrise”) and is an agreement
            between Sunrise and you (“Customer”). These Terms of Use take effect when
            Customer clicks or checks the applicable button or box associated with these
            Terms of Use or, if earlier, when Customer uses any of the Services (“<b>Effective Date</b>”).
          </p>

          <p>
            <b>
              Sunrise may update these Terms of Use from time to time and will notify
              Customer of such updates either via email, sms or by posting notice of such
              changes on its website, or other reasonable means. Customer’s continued use of the
              Services after Sunrise’s provision of a notice of an updated version of these Terms of
              Use shall constitute Customer’s consent to such updated Terms of Use.
            </b>
          </p>

          <p><b>1.  Terms of Service.</b></p>

          <p class="underline">1.1. Access and Use of Services</p>

          <p>
            Customer may access and use the scholarship website management technology
            and services offered by Sunrise on its web site at
            <a class="red-link" target="_blank" href="https://scholarship.app/">scholarship.app</a> (“<b>Website</b>”)
            to which Customer has subscribed or agreed to accept (“<b>Services”</b>), in accordance
            with these Terms of Use. The Website is exclusively the property of Sunrise. The
            Services shall be integrated with the services provided by Apply Me Inc., d/b/a Scholarship
            Owl and scholarship.app ("<b>Scholarship Owl</b>") on their website
            <a class="red-link" target="_blank" href="https://scholarshipowl.com/">ScholarshipOwl.com.</a>
          </p>

          <p>
            As part of the provision of the Services, Customers may administer
            their respective websites ("<b>Customer Website</b>") and any content uploaded
            and/or added to the Customer Website by Customer (“<b>Customer Content</b>”).
            Customers are solely responsible for their Customer Content. Sunrise does
            not review or monitor content posted by Customers and is not responsible for
            actions arising from Customer Content.
          </p>

          <p>
            Without limiting the foregoing, the permission granted to Customer
            to access and use the Services is based on Customer’s agreement to not
            access or use the Services for: (i) developing a product or service that
            could reasonably be regarded as being competitive with the Services, or (ii)
            monitoring the availability, performance or functionality of the Services, or
            for any other benchmarking or competitive purpose.
          </p>

          <p>
            1.2. <span class="underline">Additional Services.</span>
            Sunrise may from time to time offer additional services (“<b>Additional Services</b>”).
            If Customer chooses to accept or purchase Additional Services, Customer may be required to
            enter into a specific agreement for such Additional Services (“<b>Additional Agreement</b>”),
            provided that the terms of these Terms of Use will continue to apply to such Addition
            Services unless specifically amended by or conflicting with the terms of such Additional
            Agreement. Unless otherwise provided, the term “Services” shall include also all of the Additional Services.
          </p>

          <p>
            1.3. <span class="underline">Customer Account.</span>
            To access the Services, Customer must create an account associated with a
            valid email address (“<b>Account</b>”). Customer is solely responsible for
            Customer’s Account, including for: (i) controlling the access to,
            and use and security of, the Account and Account Content (as defined below),
            (ii) maintaining the security of the passwords and other measures used
            to protect access to the Account, and (iii) all instructions provided
            to Sunrise through the Account, whether or not authorized by Customer.
            Sunrise is not responsible for unauthorized access to the Account. Customer
            will contact Sunrise immediately if Customer believes an unauthorized third party
            may be using the Account. Nothing in the foregoing requires Sunrise to monitor the
            Account for unauthorized access or any other security breach.
          </p>

          <p>
            “<b>Account Content</b>” shall mean any information Customer has provided or
            has been collected in connection with establishing the Account or using the Services.
          </p>

          <p>
            1.4. <span class="underline">Separate Services; Disclaimer</span>
            Sunrise may from time to time make available to Customer, either through
            the Services or through a separate correspondence to Customer, third party
            services or offers that are not included as part of the Services (“<b>Separate Services</b>”).
            Customer hereby provides its affirmative consent to receive offers for Separate Services
            through email or sms, such consent which may be withdrawn at any time at Customer’s discretion
            by sending an email to
            <a class="red-link" href="mailto:unsubscribe@scholarship.app">unsubscribe@scholarship.app</a>.
            Customer hereby agrees to review, all terms
            of service and other agreements related to all Separate Services. Customer shall be solely
            responsible for strict compliance with any such terms of service or other agreements and from any
            liability arising from Customer’s use of the Separate Services. NOTWITHSTANDING ANYTHING TO THE
            CONTRARY IN THESE TERMS OF USE, SUNRISE MAKES NO REPRESENTATIONS OR WARRANTIES REGARDING THE SEPARATE
            SERVICES AND SHALL NOT BE RESPONSIBLE FOR ANY DAMAGES CAUSED BY THE SEPARATE SERVICES, INCLUDING
            WITHOUT LIMITATION ANY DAMAGES ARISING OUT OF ANY MISREPRESENTATIONS OR VIOLATIONS OF LAW, AND AS
            BETWEEN CUSTOMER AND SUNRISE, SEPARATE SERVICES ARE PROVIDED “AS IS” AND WITHOUT WARRANTY OF ANY KIND.
          </p>

          <p>
            1.5. <span class="underline">Third Party Services.</span>
            Certain components of the Services may be comprised of third party services,
            including by Scholarship Owl (“<b>Third Party Services</b>”), and Sunrise may perform
            its obligations hereunder through the third party providers of such Third Party
            Services (“<b>Third Party Service Providers</b>”). Without derogating from the limitation
            of liability and disclaimer of warranty provisions of these Terms of Use, Sunrise shall
            be responsible to Customer for Third Party Services to no greater extent than the same
            extent as such Third Party Service Providers are liable to Sunrise for provision of the
            Third Party Services. Sunrise may at any time, in its sole discretion, replace its Third
            Party Service Providers.
          </p>

          <p>
            1.6. <span class="underline">Services Changes.</span>
            Sunrise may modify, suspend or discontinue any of the Services
            or change or remove features or functionality of the Services or
            prices applicable thereto from time to time, without any liability to
            Customer. Sunrise will not be liable to Customer or to any third party
            for any modification, suspension or discontinuance of the Services. Sunrise
            will use commercially reasonable efforts to notify Customer of any material change
            to or discontinuation of the Services.
          </p>

          <p>
            1.7. <span class="underline">Suspension of Services.</span>
            At any time Sunrise may suspend the provision of Services and remove any Account
            Content and/or Customer Content  transmitted via the Services without liability: (i)
            if Sunrise reasonably believes that any Service is being used in violation of these
            Terms of Use or applicable law, (ii) if Sunrise reasonably believes that the use of the
            Services may pose a security risk to Sunrise or any third party, (iii) if ordered by a law
            enforcement or government agency or otherwise in order to comply with applicable law, (iv) if
            necessary for operation, maintenance or expansion of Sunrise’s Services, or for other technological
            purposes, (v) if required or requested by a Third Party Service Provider or if the Third Party Service
            is suspended or ceases to be provided for any reason (whether generally or through Sunrise), or (vi) if
            Customer fails to fulfill payment obligations. To the extent practicable, Sunrise shall use commercially
            reasonable efforts to notify Customer prior to suspending Services, except that no notice is required prior
            to suspension due to not fulfilling payment obligations. Account information may be unavailable to Customer
            during a suspension of Services.
          </p>

          <p>
            1.8. <span class="underline">Privacy Policy; Site Terms.</span>
            Customer consents to the collection, use and disclosure of information
            associated with Customer and Customer's use of the Website and the Services
            in accordance with these Terms of Use, and the Sunrise Privacy Policy; all as
            may be updated from time to time.
          </p>

          <br>

          <p>2. <b>Fees &amp; Billing.</b></p>

          <p>
            2.1. <span class="underline">Fees.</span>
          </p>

          <p>
            In consideration for Customer’s usage of the Services, Customer agrees to pay
            Sunrise fees at Sunrise’s then-current pricing for the Services as published from
            time to time on the Website. All payments from Customer to Sunrise must be in United States Dollars.
          </p>

          <p>
            Sunrise may in its sole discretion modify its pricing upon the provision of at least 21 days’
            notice to already-paying customers, by posting on the Website and/or sending an email or sms
            to the address maintained for the relevant Account.
          </p>

          <p>
            Unless Customer notifies Sunrise in writing of Customer’s termination of its use of the Services
            within 14 days after delivery of such notice, the updated pricing shall apply to Customer’s access
            to and use of the Services
          </p>

          <p>
            2.2. <span class="underline">Billing.</span>
          </p>

          <p>
            Sunrise will bill Customer for the Services ordered by Customer, through Customer's ’s credit card /
            debit card / PayPal account / Customer's designated bank account or ACH, either as a one-time payment,
            or in automatically reoccurring payments if Customer has an ongoing subscription. Sunrise shall send a
            sales receipt to Customer upon request.
          </p>

          <p>
            Customers with ongoing subscriptions are responsible for maintaining current and complete credit card /
            debit card / PayPal account on file with Sunrise at all times. Changes to this information must be
            communicated to Sunrise at
            <a class="red-link" href="mailto:contact@scholarship.app">contact@scholarship.app.</a>
          </p>

          <p>
            All charges are automatically made on Customer’s specific billing cycle date.
          </p>

          <p>
            Sunrise shall have the right to suspend Customer’s Account without prior notice once payment
            is overdue by 10 calendar days.
          </p>

          <p>
            TO THE FULLEST EXTENT PERMITTED BY LAW, CUSTOMER WAIVES ALL CLAIMS RELATING TO CHARGES ASSOCIATED
            WITH THE SERVICES UNLESS CLAIMED WITHIN SIXTY (60) DAYS AFTER THE CHARGE.
          </p>

          <p>
            All payments are non-refundable and will not be prorated at any time.
          </p>

          <p>
            2.3. <span class="underline">Late Fees.</span>
          </p>

          <p>
            Sunrise may charge interest on overdue amounts at the lesser of 1.5% per month
            and the maximum rate permitted by law, plus all reasonable expenses and costs of
            collection (including collection agency fees, attorney fees and court costs). The
            assessment of interest shall not limit Sunrise’s other rights and remedies for late
            payment, including Sunrise’s right to suspend or terminate access to the Services.
          </p>

          <p>
            2.4. <span class="underline">Taxes.</span>
          </p>

          <p>
            Customer is responsible for all taxes, fees and duties, including penalties and interest, imposed
            in connection with these Terms of Use (“<b>Taxes</b>”). Customer will pay Sunrise its fees without
            reduction for Taxes. If Sunrise is obligated to collect or pay Taxes, the Taxes
            will be invoiced to Customer, unless Customer provides Sunrise on a timely basis with
            a valid tax exemption certificate authorized by the appropriate taxing authority
            (along with any supporting documentation that Sunrise may require in connection with such
            tax exemption certificate). If any deduction or withholding is required by law, Customer will
            notify Sunrise and pay Sunrise any additional amounts necessary to ensure that the net amount that
            Sunrise receives, after any deduction and withholding, equals the amount Sunrise would have received
            if no deduction or withholding has been required. Customer shall provide to Sunrise original or certified
            copies of all Tax payments or other evidence of payment of Taxes by Customer with respect to transactions
            or payments under these Terms of Use.
          </p>

          <p>
            2.5. <span class="underline">Third Party Fees.</span>
            Any third-party fees (including credit card, debit card, PayPal and bank fees)
            (“<b>Third Party Fees</b>”) are Customer’s sole responsibility, and if charged to Sunrise,
            Customer shall pay Sunrise an additional amount equaling the Third Party Fees in order
            to ensure that the net amount that Sunrise receives equals the amount Sunrise would have
            received if no Third Party Fees had been charged.
          </p>

          <p>
            3. <b>Term and Termination.</b>
          </p>

          <p>
            3.1. <span class="underline">Term.</span>
            These Terms of Use will commence on the Effective Date and will remain in effect until terminated
            by either party in accordance with the terms hereof. Customer is encouraged to keep records of all
            communications regarding termination.
          </p>

          <p>
            3.2. <span class="underline">Termination for Convenience</span>
            Each party may terminate these Terms of Use and Customer’s right to access and use the Services
            by providing the other party with 7 business days prior notice.
          </p>

          <p>
            Customer’s mere cancelation of its credit card / debit card / PayPal account associated
            with its Account does not terminate these Terms of Use or Customer’s obligation to pay for
            the Services. In such case, Sunrise will continue to the Account as active and the billing cycle
            will continue, resulting in a past due account that may be turned over to a third party collection
            service. Customer must terminate these Terms of Use and Customer’s right to access and use the Services
            by notifying Sunrise through the Account or by telephone.
          </p>

          <p>
            3.3. <span class="underline">Termination for Breach and Other Reasons.</span>
            Sunrise may terminate these Terms of Use and Customer’s right to access and use the Services
            immediately and without liability upon providing notice to Customer as follows: (i) if Sunrise
            reasonably suspects a breach of these Terms of Use or violation of applicable law, (ii) if Sunrise
            is threatened with a legal claim, including for intellectual property infringement, related to the
            Services, (iii) if Customer’s act or omission results in a suspension described in Section 1.8, (iv)
            if Sunrise’s relationship with a Third Party Services Provider expires, terminates or requires Sunrise
            to change the Services or terminate these Terms of Use, (v) if Sunrise believes providing the Services
            could create an economic or technical burden or security risk, (vi) in order to comply with applicable law
            or requests of governmental entities, or (vii) if Sunrise determines that the use of the
            Services by Customer or Sunrise’s provision of any of the Services has become impractical or
            unfeasible for any legal or regulatory reason.
          </p>

          <p>
            3.4. <span class="underline">Effect of Termination.</span>
            If these Terms of Use is terminated, then: (i) Sunrise shall immediately terminate
            Customer’s access to the Services, (ii) all fees and Taxes that Customer is obliged to
            pay Sunrise, up to the effective date of termination, will be charged at the end of the
            then-current billing period, (iii) there will be no refund of any amount already paid by
            Customer as of the effective date of termination, including any amount in respect of the
            period following the effective date of termination, and (iv) Sunrise shall maintain Account Content.
          </p>

          <p>
            3.5. If these Terms of Use are terminated (whether at Customer's request or at Sunrise’s discretion),
            it may cause or result in the loss of certain content, features, or capacity of the Customer Website,
            including any Customer Content, End User data or other usage data retained therein, and including
            any domain name reservation or registration that was included in such Services (“<b>Capacity Loss</b>”).
            Sunrise shall not be liable in any way for such Capacity Loss, or for saving a backup of Customer
            Content or End User data. Additional Fees may apply to re-activation of an Account and/or any Services
            following their cancellation, as determined by Sunrise in its sole discretion.
          </p>

          <br>

          <p>4 <b>Acceptable Use.</b></p>

          <p>
            4.1. Customer shall use the Services in compliance with all applicable laws, ordinances,
            rules and regulations, shall not violate or attempt to violate Sunrise’s system or network security,
            and shall not misuse the Services in any way. Customer shall not and shall not permit third parties to
            (i) engage in, solicit, or promote any activity that is illegal, invasive, violates the rights of
            others, or could subject Sunrise to liability to third parties, (ii) import, upload, copy, use or
            publish Customer Content that is defamatory, misleading, fraudulent, obscene, distasteful,
            harassing, discriminatory, racially or ethnically offensive, contains sexually suggestive
            or explicit content, facilitates or promotes illegal activity, or contains illegal
            content, (iii) distribute viruses, worms, Trojan horses, corrupted files, hoaxes, or
            other items of a disabling, destructive, damaging, or deceptive nature, (iv) use any of the
            Services in connection with any form of spam, unsolicited mail, fraud, scam, phishing, “chain
            letters”, “pyramid schemes” or similar conduct, or otherwise engage in unethical marketing
            or advertising, (v) offer, promote or sell any scholarships, products or services, or provide
            any information, content or materials regarding scholarships, services or products, which may be
            deemed fraudulent or with no intention and/or ability to provide any such scholarships, services
            or products, as applicable, (vi) block, mask or obscure advertisements that appear on the Website
            without Sunrise's prior written approval, or (vii) disable, interfere with, or circumvent any aspect
            of the Services.
          </p>

          <p>
            4.2. It is clarified that Sunrise does not monitor in any way Customer Content, and it is Customer’s
            sole responsibility to monitor the content imported, uploaded, copied, used or published by Customer
            or on Customer's behalf, pursuant to the provisions of this Section 4. In the event there
            is concern regarding any violation of these Terms of Use by Customer, Sunrise is authorized,
            in its sole discretion, to monitor Customer’s compliance, to immediately terminate or suspend
            the provision of Services to Customer, or to block the transmission of Customer Content which in
            Sunrise’s opinion violates any application law or otherwise may impose any liability on Sunrise.
          </p>

          <br>

          <p>5. <b>Publicity.</b></p>

          <p>
            Customer hereby irrevocably and in perpetuity grant to Sunrise and those acting under Sunrise’s
            authority the unrestricted, absolute, perpetual, worldwide right and license to use Customer’s
            name, trademarks, address, photograph, information, statements, and Customer Content
            (collectively, “<b>Promotional Content</b>”), all in connection with marketing purposes; and
            to reproduce, copy, modify, create derivative works of, display, perform, exhibit, distribute,
            transmit or broadcast, publicly or otherwise, or otherwise use and permit to be used, the
            Promotional Content or any part thereof, whether alone or in combination with other materials
            (including but not limited to text, data, images, photographs, illustrations, and graphics, video
            or audio segments of any nature), in any media whatsoever, in connection with such marketing purposes;
            and all the foregoing without any compensation, royalties, remuneration or consideration to Customer or
            to any third party, and Customer hereby waive all claims to compensation,  royalties, remuneration,
            consideration, notice or permission in connection therewith.
          </p>

          <br>

          <p>6. <b>Customer’s Warranties and Obligations.</b></p>

          <p>
            6.1. Customer represents and warrants to Sunrise that:
          </p>

          <p>
            a. Customer has the requisite power and authority to enter into these Terms of Use and
            into any Additional Agreement, and to perform all of Customer’s obligations hereunder,
          </p>

          <p>
            b. Customer is a natural person over the age of 13, if Customer is an individual.
          </p>

          <p>
            c. Customer is not named on any U.S. Government denied party list.
          </p>

          <p>
            d. To Customer's knowledge, there is no action, suit or proceeding, or governmental
            inquiry or investigation, pending or threatened against Customer, and, to Customer's
            knowledge, there is no basis for any such action, suit, proceeding, or governmental
            inquiry or investigation
          </p>

          <p>
            e. Customer shall update Customer’s Account information to maintain the accuracy of Customer’s
            Content during the term of these Terms of Use.
          </p>

          <p>
            f. Customer owns all rights in and to any content uploaded or provided by Customer, or imported,
            copied or uploaded by the Services for Customer, including any designs, images, animations, videos,
            audio files, fonts, logos, code, illustrations, compositions, artworks, interfaces, usernames, text
            and any other materials (any such content shall be part of the Customer Content), or otherwise have
            (and will continue to have) the full power, title, licenses, consents and authority, in and to the content,
            as necessary to legally access to, import, copy, use, publish, transfer or license such Customer Content,
            by Customer and Sunrise or any of Sunrise's affiliates.
          </p>

          <p>
            g. Customer Content is (and will continue to be) true, current, accurate, non-infringing
            upon any third-party rights, and in no way unlawful for Customer to upload, import, copy,
            possess, post, transmit, display or otherwise use, in the country in which Customer or Customer
            Website visitors and users (“<b>End Users</b>”) reside, or for Sunrise and/or Customer End Users to access,
            import, copy, upload, use or possess in connection with the Services;
          </p>

          <p>
            h. Customer has obtained all consents and permissions required under all applicable laws,
            regarding the posting, transmission and publication of any personal information and/or image
            or likeness of any person, entity or property which is part of the Customer Content, and Customer
            will adhere to all laws applicable thereto.
          </p>

          <p>6.2. Customer undertakes and agrees to:</p>

          <p>
            a. fully comply with all applicable laws and any other contractual terms which govern Customer
            use of the Services (and any related interaction or transaction), including those specific laws
            applicable to Customer or Customer's End Users in any of Customer's geographical locations;
          </p>

          <p>
            b. be solely responsible and liable with respect to any of the uses of the Services which
            occur under Customer Account, and for any of Customer's Content (including for any consequences
            of accessing, importing, uploading, copying, using or publishing such Customer Content on or with
            respect to the Services);
          </p>

          <p>
            c. regularly and independently save and backup any of Customer Content, including with respect
            to End Users, and any applications and/or third-party services used by Customer;
          </p>

          <p>
            d. receive from time to time promotional messages and materials from Sunrise or its partners,
            by mail, e-mail or any other contact form Customer may provide Sunrise with. If Customer wishes
            not to receive such promotional materials or notices – please just notify Sunrise at any time;
          </p>

          <br>

          <p>
            7. <b>Indemnification</b>
          </p>

          <p>
            Customer will defend, indemnify, and hold harmless Sunrise, its affiliates and licensors, and
            each of their respective employees, officers, directors, agents and representatives, from
            and against any claims, damages, losses, liabilities, costs, fines and expenses (including
            reasonable attorneys’ fees) arising out of or relating to: (i) Customer’s actual or alleged use
            of the Services (including any activities under Customer’s Account), (ii) breach of these Terms
            of Use, any Additional Agreement, any representation or warranty of Customer (including breach
            of any terms of use or agreement with any provider of Separate Services) or any violation of
            applicable law, or (iii) Customer’s Content, including any claim involving alleged infringement
            or misappropriation of third-party rights by or in connection with Customer Content.
          </p>

          <p>
            Sunrise shall promptly notify Customer of any claim subject to indemnification, provided that
            Sunrise’s failure to do so shall not affect Customer’s obligations hereunder, except to the
            extent that Sunrise’s failure to promptly notify Customer materially delays or prejudices
            Customer’s ability to defend the claim. At Sunrise’s option, Customer will have the right to
            defend against any such claim with counsel of Customer’s own choosing (subject to Sunrise’s written
            consent) and to settle such claim as Customer deem appropriate, provided that Customer shall not enter
            into any settlement without Sunrise’s prior written consent and provided that Sunrise may, at any time,
            elect to take over control of the defense and settlement of the claim.
          </p>

          <br>

          <p>8. <b>Disclaimers of Warranties and Limitation of Liability.</b></p>

          <p>
            8.1. <span class="underline">Disclaimer of Warranties. </span>
            NOTWITHSTANDING ANY OTHER PROVISION IN THESE TERMS OF USE, ANY ADDITIONAL
            AGREEMENT OR SUNRISE’S PRIVACY POLICY, THE SERVICES ARE PROVIDED ON AN “AS IS”
            AND “AS AVAILABLE” BASIS. SUNRISE AND ITS AFFILIATES MAKE NO REPRESENTATIONS OR
            WARRANTIES OF ANY KIND (EXPRESS, IMPLIED, STATUTORY OR OTHERWISE) REGARDING THE SERVICES,
            ADDITIONAL SERVICES, THIRD PARTY SERVICES, OR SEPARATE SERVICES
            (COLLECTIVELY, “<b>ACCUMULATED SERVICES</b>”),
            INCLUDING ANY WARRANTY THAT THE ACCUMULATED SERVICES WILL BE UNINTERRUPTED, ERROR FREE OR FREE OF
            HARMFUL COMPONENTS, THAT ANY CONTENT, INCLUDING CUSTOMER’S CONTENT OR THIRD PARTY CONTENT, WILL
            BE SECURE OR NOT OTHERWISE LOST OR DAMAGED, OR THAT THERE WILL NOT BE ANY TYPOGRAPHICAL OR OTHER
            ERRORS IN APPLICATIONS. SUNRISE DOES NOT GUARANTEE THE RECEIPT AND/OR PROVISION OF ANY SCHOLARSHIP,
            AND CUSTOMER WAIVES ANY CLAIM AGAINST SUNRISE IN CONNECTION THEREWITH, EVEN IF CUSTOMER AND/OR CUSTOMER'S
            END USER WAS OTHERWISE ENTITLED TO SUCH SCHOLARSHIP. EXCEPT TO THE EXTENT PROHIBITED BY
            LAW, SUNRISE AND ITS AFFILIATES HEREBY DISCLAIM ALL WARRANTIES, INCLUDING IMPLIED WARRANTIES
            OF MERCHANTABILITY, SATISFACTORY QUALITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT,
            AND WARRANTIES ARISING OUT OF THE COURSE OF DEALING OR USAGE OF TRADE. CUSTOMER ACKNOWLEDGES THAT
            THERE ARE RISKS INHERENT IN INTERNET CONNECTIVITY AND THE SCHOLARSHIP WEBSITE MANAGEMENT TECHNOLOGY
            THAT COULD RESULT IN THE LOSS OF CUSTOMER CONTENT, PRIVACY, DATA, CONFIDENTIAL INFORMATION, AND PROPERTY.
          </p>

          <p>
            8.2. <span class="underline">Limitation of Liability.</span>
            NOTWITHSTANDING ANY OTHER PROVISION IN THESE TERMS OF USE, ANY ADDITIONAL AGREEMENT OR SUNRISE’S
            PRIVACY POLICY, IN NO EVENT SHALL SUNRISE OR ANY OF ITS AFFILIATES, OR ANY OF THEIR EMPLOYEES,
            OFFICERS, DIRECTORS, AGENTS, OR SUPPLIERS, BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
            EXEMPLARY OR CONSEQUENTIAL DAMAGES (COLLECTIVELY “<b>DAMAGES</b>”), HOWEVER CAUSED AND UNDER ANY THEORY OF
            LAW (INCLUDING BREACH OF CONTRACT, TORT, STRICT LIABILITY, AND INFRINGEMENT), EVEN IF ADVISED OF THE
            POSSIBILITY OF DAMAGES AND EVEN IF DAMAGES ARE FORESEEABLE. THIS LIMITATION OF LIABILITY INCLUDES BUT
            IS NOT LIMITED TO THE FOLLOWING DAMAGES: (A) CUSTOMER’S INABILITY TO USE ANY OF THE ACCUMULATED SERVICES,
            INCLUDING AS A RESULT OF ANY (I) TERMINATION OR SUSPENSION OF THESE TERMS OF
            USE OR CUSTOMER’S USE OF OR ACCESS
            TO THE SERVICES, (II) SUNRISE’S DISCONTINUATION OF ANY OF THE SERVICES, OR (III) DOWNTIME,
            INCLUDING ANY UNANTICIPATED
            OR UNSCHEDULED DOWNTIME OF ALL OR A PORTION OF THE SERVICES FOR ANY REASON, INCLUDING AS A RESULT OF
            ERRORS IN THE SCHOLARSHIP WEBSITE MANAGEMENT TECHNOLOGY, POWER OUTAGES,
            SYSTEM FAILURES OR OTHER INTERRUPTIONS,
            (B) THE COST OF OR TIME INVOLVED IN THE PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES, (C) ANY INVESTMENTS,
            EXPENDITURES, LOST OPPORTUNITIES, OR COMMITMENTS BY CUSTOMER IN CONNECTION WITH THESE TERMS OF USE OR ANY
            ADDITIONAL AGREEMENT OR CUSTOMER’S USE OF OR ACCESS TO THE ACCUMULATED SERVICES, AND (D) ANY UNAUTHORIZED
            ACCESS TO, ALTERATION OF, OR THE DELETION, DESTRUCTION, DAMAGE, LOSS OR FAILURE TO
            STORE ANY OF CUSTOMER’S CONTENT.
          </p>

          <p>
            NOTWITHSTANDING ANY OTHER PROVISION IN THESE TERMS OF USE, ANY ADDITIONAL AGREEMENT OR
            SUNRISE’S PRIVACY POLICY, THE MAXIMUM AGGREGATE LIABILITY OF SUNRISE OR ANY OF ITS AFFILIATES,
            OR ANY OF THEIR EMPLOYEES, OFFICERS, DIRECTORS, AGENTS OR SUPPLIERS, UNDER ANY THEORY OF
            LAW (INCLUDING FOR BREACH OF CONTRACT, TORT, STRICT LIABILITY, OR INFRINGEMENT), SHALL NOT
            EXCEED THE AMOUNT OF FEES ACTUALLY RECEIVED BY SUNRISE FROM CUSTOMER FOR THE SIX MONTHS
            IMMEDIATELY PRECEDING THE FIRST CLAIM UNDER THESE TERMS OF USE OR ANY ADDITIONAL AGREEMENT.
            THIS IS AN AGGREGATE LIMIT. THE EXISTENCE OF MORE THAN ONE CLAIM SHALL NOT ENLARGE THIS LIMIT.
          </p>

          <p>
            CUSTOMER ACKNOWLEDGES THAT THE FOREGOING DISCLAIMERS OF WARRANTIES AND LIMITATIONS OF
            LIABILITY ARE ESSENTIALS PART OF THESE TERMS OF USE AND ABSENT SUCH DISCLAIMERS AND
            LIMITATIONS SUNRISE WOULD NOT AGREE TO PROVIDE ANY SERVICES TO CUSTOMER OR ENTER INTO THESE
            TERMS OF USE OR ANY ADDITIONAL AGREEMENT.
          </p>

          <p>
            THE LAWS OF CERTAIN JURISDICTIONS DO NOT ALLOW LIMITATIONS ON IMPLIED WARRANTIES OR
            THE EXCLUSION OR LIMITATION OF CERTAIN DAMAGES. IF SUCH LAWS APPLY TO CUSTOMER, SOME
            OR ALL OF THE ABOVE DISCLAIMERS OR LIMITATIONS MAY NOT APPLY, AND CUSTOMER MAY HAVE
            ADDITIONAL RIGHTS.
          </p>

          <br>

          <p>9. <b>Proprietary Rights and Confidentiality.</b></p>

          <p>
            9.1. <span class="underline">Customer's Content.</span>
            As between Sunrise and Customer, Customer owns all right, title and interest in and to
            the Customer Content. Notwithstanding the aforementioned, in any event that the
            Services (including Additional Services) are provided to the Customer for free,
            Sunrise shall have the right to use any End User data provided by End Users to Customer
            or collected by the Customer from Customer's End Users through Customer Website in accordance
            with and subject to the provisions of the [Sunrise Privacy Policy], including for the
            purposes of marketing and other commercialization, and Customer shall ensure that Customer's
            privacy policy permits such use by Sunrise.
          </p>

          <p>
            9.2. <span class="underline">Services.</span>
            As between Sunrise and Customer, Sunrise or its licensors own and reserve all rights,
            title and interest in and to the Services, Sunrise’s trademarks and service marks and all
            modifications to any of the forgoing and all related intellectual property rights, including,
            without limitation, all content accessible from the Website (other than the Customer Content).
          </p>

          <p>
            9.3. <span class="underline">Suggestions.</span>
            Should Customer provide Sunrise with any suggestions or ideas pertaining to the Services
            (“<b>Suggestions</b>”), Sunrise will own all right, title and interest in and to the Suggestions
            and will be free to use the Suggestions for any purpose without any liability or payment to
            Customer. Customer hereby irrevocably assigns to Sunrise all right, title and interest in and
            to Suggestions and agrees to provide Sunrise with reasonable assistance, at Sunrise’s cost, required
            or desirable in order to document, perfect and maintain Sunrise’s rights in the Suggestions.
          </p>

          <p>
            9.4. <span class="underline">Confidentiality.</span>
            Under these Terms of Use, Customer may be entrusted with or may learn information not generally
            known to the public, that Customer should reasonably understand to be confidential, such as trade
            secrets, client lists, inventions, innovations, processes, information, specifications,
            businessand product processes, methods, accounts, account passwords and procedures
            (collectively, "<b>Confidential Information</b>"). Customer agrees not to use any Confidential
            Information of Sunrise except in connection with Customer’s authorized use of the
            Services. Customer agrees that Customer will not disclose any Confidential Information,
            directly or indirectly, to any third party, except as required by applicable law. All
            Confidential Information relating to the business of Sunrise, including files, records,
            documents, notes and similar items, whether prepared by Sunrise or by Customer or otherwise,
            shall remain the exclusive property of Sunrise. Whenever requested by Sunrise, Customer shall
            immediately destroy or deliver to Sunrise all originals and copies of all written,
            photographic, electronic and other documentation containing Confidential Information that
            is under Customer's possession or control. If no request is made by Sunrise, then Customer
            shall permanently delete and destroy all such documentation fourteen (14) calendar days of
            the expiration or termination of these Terms of Use. Customer agrees to indemnify and hold
            Sunrise harmless from and to defend it against all claims and liability resulting from
            Customer's access to the Confidential Information. The terms of this paragraph shall survive
            the expiration or termination of these Terms of Use.
          </p>

          <br>

          <p>10. <b>Governing Law &amp; Jurisdiction.</b></p>

          <p>
            These Terms of Use will be governed by and construed in accordance with the laws of the
            State of Delaware without giving effect to any conflict of laws and provisions that would
            require the application of the laws of any other jurisdiction. The parties hereby
            expressly reject any application to these Terms of Use of: (i) the United Nations Convention
            on Contracts for the International Sale of Goods, and (ii) the 1974 Convention on the
            Limitation Period in the International Sale of Goods, as amended. All disputes arising out
            of these Terms of Use will be subject to the exclusive jurisdiction of the competent courts
            located in the State of Delaware, and the parties agree and submit to the personal and exclusive
            jurisdiction and venue of these courts, except that nothing will prohibit Sunrise from
            instituting an action in any court of competent jurisdiction to obtain injunctive relief
            or protect or enforce its intellectual property rights or to otherwise protect its interests
            in the Services.
          </p>

          <br>

          <p>11. <b>Miscellaneous.</b></p>

          <p>
            11.1. <span class="underline">Non-Waiver</span>
            A party’s failure or delay in enforcing any provision of these Terms of Use will not
            be deemed a waiver of that party’s rights with respect to that provision or any other
            provision of these Terms of Use. A party’s waiver of any of its rights under these Terms
            of Use is not a waiver of any of its other rights with respect to a prior, contemporaneous
            or future occurrence, whether similar in nature or not.
          </p>

          <p>
            11.2. <span class="underline">Construction.</span>
            The headings in these Terms of Use are not part of these Terms of Use but are solely
            for the convenience of the parties. As used herein, the words “include” and “including”
            and variations thereof, will not be deemed to be terms of limitation, but rather will be
            deemed to be followed by the words “without limitation.” All references in these Terms of
            Use to “Sections” refer to sections herein.
          </p>

          <p>
            11.3. <span class="underline">Survival.</span>
            The terms of the following Sections shall survive any expiration or termination of
            these Terms of Use:  [Additional Services],  [Separate Services],  [Privacy Policy, Site Terms],
            [Fees &amp; Billing],  [Term and Termination],  [Publicity],  [Customer Warranties and Obligations],
            [Indemnification],  [Disclaimers of Warranties and Limitation of Liability],
            [Proprietary Rights and Confidentiality],  [Governing Law &amp; Jurisdiction] and  [Miscellaneous].
          </p>

          <p>
            11.4. <span class="underline">Force Majeure</span>
            Neither party shall be in default of any obligation under these Terms of Use if the failure
            to perform the obligation is due to any event beyond that party’s reasonable control, including
            electrical or internet failure, natural disaster, war, riot, insurrection, epidemic, strikes or
            other organized labor action, terrorist activity, or events of a magnitude or type for which precautions
            are not generally taken in the industry.
          </p>

          <p>
            11.5. <span class="underline">Severability.</span>
            If any provision of these Terms of Use is held to be unenforceable for any reason, in whole or
            in part, such decision shall not affect the validity or enforceability of any or all of the
            remaining portions thereof. Any such unenforceable provision or portion thereof shall be changed
            and interpreted so as to best accomplish the objectives of such provision or portion thereof within
            the limits of applicable law.
          </p>

          <p>
            11.6. <span class="underline">Assignment.</span>
            Customer may not assign these Terms of Use without the prior written consent of Sunrise.
          </p>

          <p>
            11.7. <span class="underline">Notices.</span>
            Sunrise may provide notice of changes to these Terms of Use to Customer either via e-mail or
            by posting a notice of such changes on Sunrise’s web site. Customer may provide notice to Sunrise
            by courier, mail, facsimile or e-mail, provided that receipt of such notice is verified in writing
            by Sunrise. Sunrising can be reached at
            <a class="red-link" href="mailto:contact@scholarship.app">contact@scholarship.app</a>, or at Apply Me Inc.,
            at <a class="red-link" href="mailto:contact@apply.me">contact@apply.me</a>.
          </p>

          <p>
            11.8. <span class="underline">Entire Agreement.</span>
            These Terms of Use, along with Owl’s other policies (including its Privacy Policy)and terms
            referenced herein, are the complete and exclusive agreement between the parties regarding its
            subject matter herein and supersedes and replaces any other written or oral agreement, understanding
            or communication regarding such subject matter.
          </p>
        </div>
        <div class="bottom-shadow"/>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'TermsPopup',

  props: {
    visible: {
      type: Boolean,
      default: true,
    },
  },

  methods: {
    closeModal() {
      this.$emit('close');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '~assets/css/_variables.scss';
@import '~assets/css/_mixins.scss';

.popup {
  position: fixed;
  z-index: 1000;
  display: flex;
  justify-content: center;
  align-items: center;
  top: 0;
  width: 100vw;
  height: 100vh;
  overflow: hidden;

  .background {
    position: absolute;
    width: 100vw;
    height: 100vh;
    background: rgba(236, 236, 236, 0.4);
    z-index: 1;
    filter: blur(20px);
  }

  .content {
    position: relative;
    z-index: 2;
    background-color: $white;
    width: 100%;
    height: 100%;
    max-height: 100vh;
    overflow: hidden;
    padding: 14px;

    @include sun-desktop {
      max-height: 642px;
      max-width: 781px;
      box-shadow: 0px 4px 55px rgba(0, 0, 0, 0.25);
      border-radius: 20px;
      padding: 47px 32px 63px 79px;
    }

    @include sun-hd {
      max-height: 80vh;
      max-width: 40vw;
    }

    .close-btn {
      position: absolute;
      top: 12px;
      right: 12px;
      width: 25px;
      height: 25px;
      border-radius: 50%;
      background-color: #CE818C;
      border: none;
      display: flex;
      justify-content: center;
      align-items: center;

      @include sun-desktop {
        top: 22px;
        right: 22px;
        width: 40px;
        height: 40px;
      }

      img {
         width: 8px;
         height: 8px;

        @include sun-desktop {
          width: 16px;
          height: 16px;
        }
      }
    }

    .text {
      overflow-y: scroll;
      padding-right: 12px;
      max-height: calc(100% - 63px);
      font-size: 15px;

      @include sun-hd {
        font-size: 19px;
      }

      /* width */
      &::-webkit-scrollbar {
          width: 6px;
      }
      /* Track */
      &::-webkit-scrollbar-track {
          background: #ECECEC;
          border-radius: 90px;
      }
      /* Handle */
      &::-webkit-scrollbar-thumb {
          background: #CE818C;
          border-radius: 90px;
      }
      /* Handle on hover */
      &::-webkit-scrollbar-thumb:hover {
          background: #edaab4;
          border-radius: 90px;
      }

      p:last-child {
        margin-bottom: 100px;
      }
    }

    .bottom-shadow {
      position: absolute;
      bottom: 0;
      z-index: 1;
      left: 0;
      right: 12px;
      height: 100px;
      background: linear-gradient(2.47deg, #FFFFFF 31.41%, rgba(255, 255, 255, 0) 85.8%);

      @include sun-desktop {
        bottom: 63px;
        right: 44px;
      }

      @include sun-hd {
        bottom: 0;
      }
    }
  }

  .modal-title {
    font-size: 20px;
    margin-bottom: 20px;
    margin-top: 0;

    @include sun-tablet {
      font-size: 28px;
    }

    @include sun-hd {
      font-size: 34px;
      margin-bottom: 40px;
    }
  }

  .underline {
    text-decoration: underline;
  }
}
</style>
